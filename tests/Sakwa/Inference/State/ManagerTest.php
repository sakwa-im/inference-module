<?php

use Sakwa\Utils\Guid;
use Sakwa\Utils\Registry;
use Sakwa\Inference\State\Manager;
use Sakwa\DecisionModel\Enum\InitializeMode;
use Sakwa\Inference\State;
use Sakwa\Inference\State\Entity\Variable;

class ManagerTest extends \PHPUnit\Framework\TestCase
{
    protected $guid;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        $this->guid = new Guid();
        $decisionModel = new \Sakwa\DecisionModel\VariableDef('foobar');
        $decisionModel->setGuid($this->guid);
        $decisionModel->setInitializeMode(InitializeMode::None);
        Registry::set('decisionModel', $decisionModel, State::getInstance()->getContext());
        Registry::getInstance($this->guid)->addKey($this->guid, $decisionModel);
    }

    /**
     * @test
     */
    public function shouldBeAbleToCheckTheExistenseOfVariables()
    {
        $manager = Manager::getInstance();

        $this->assertFalse($manager->hasVariable($this->guid));

        $manager->createVariable($this->guid, 123);
        $this->assertTrue($manager->hasVariable($this->guid));
    }

    /**
     * @test
     */
    public function shouldBeAbleToCreateVariables()
    {
        $manager = Manager::getInstance();

        $this->assertFalse($manager->hasVariable($this->guid));

        $manager->setVariable($this->guid, 123);
        $this->assertTrue($manager->hasVariable($this->guid));

        $manager->setVariable($this->guid, 456);
    }

    /**
     * @test
     */
    public function shouldBeAbleGetVariables()
    {
        $manager = Manager::getInstance();

        $this->assertFalse($manager->hasVariable($this->guid));

        $variable = $manager->getVariable($this->guid);
        $this->assertInstanceOf('\Sakwa\Inference\State\Entity\Variable', $variable);
    }

    /**
     * @test
     */
    public function shouldBeAbleToApplyBranchingLogic()
    {
        $branche1 = new Guid();
        $branche2 = new Guid();
        $branche3 = new Guid();

        $manager = Manager::getInstance();

        $manager->createVariable($this->guid, 123);
        $manager->branch($branche1);

        $this->assertEquals(123, $manager->getVariable($this->guid)->getValue());

        $manager->setVariable($this->guid, 456);
        $manager->branch($branche2);

        $this->assertEquals(456, $manager->getVariable($this->guid)->getValue());

        $manager->revert($branche1);

        $this->assertEquals(123, $manager->getVariable($this->guid)->getValue());

        $manager->branch($branche3);
        $manager->setVariable($this->guid, 789);
        $manager->commit();
        $manager->revert($branche1);

        $this->assertEquals(789, $manager->getVariable($this->guid)->getValue());

        $manager->branch();
    }

    /**
     * @test
     */
    public function shouldBeAbleToReinitializeEntitys()
    {
        $backupContext = State::getInstance()->getContext();
        State::getInstance()->createNewContext();

        $this->setUp();

        $variable = new Variable($this->guid, 123);
        $this->assertEquals(123, $variable->getValue());

        $manager = Manager::getInstance();
        $manager->reInitializeEntitys();

        $this->assertEquals(123, $variable->getValue());

        State::getInstance()->setContext($backupContext);
    }

}