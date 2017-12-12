<?php

use Sakwa\Utils\Guid;
use Sakwa\Utils\Registry;
use Sakwa\Inference\State;
use Sakwa\Inference\State\Entity\Variable;
use Sakwa\DecisionModel\Enum\InitializeMode;

class VariableTest extends \PHPUnit\Framework\TestCase
{
    protected $guid;

    /**
     * @var \Sakwa\DecisionModel\VariableDef $decisionModel
     */
    protected $decisionModel;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        $this->guid = new Guid();
        $decisionModel = new \Sakwa\DecisionModel\VariableDef('foobar');
        $decisionModel->setGuid($this->guid);
        $this->decisionModel = $decisionModel;
        Registry::set('decisionModel', $decisionModel, State::getInstance()->getContext());
        Registry::getInstance($this->guid)->addKey($this->guid, $decisionModel);
    }

    /**
     * @test
     */
    public function shouldBeAbleToSetCycleContext()
    {
        $variable = new VariableTestObject($this->guid, null, new Guid());
        $this->assertNotNull($variable->getCycleContext());
    }

    /**
     * @test
     */
    public function shouldBeAbleToSetAndGetValue()
    {
        $variable = new Variable($this->guid);
        $variable->setValue(123);
        $this->assertEquals(123, $variable->getValue());
    }

    /**
     * @test
     */
    public function shouldBeAbleToResolveValue()
    {
        $backupContext = State::getInstance()->getContext();
        $newContext = State::getInstance()->createNewContext();

        $this->setUp();

        $mockDim = $this->createMock('\Sakwa\Data\Information\Module');
        Registry::add('Sakwa\Data\Information', $mockDim, $newContext);

        $mockDim
            ->expects($this->once())
            ->method('getVariableValue')
            ->with($this->anything())
            ->will($this->returnValue(123456789));

        $variable = new Variable($this->guid);
        $this->assertEquals(123456789, $variable->getValue());

        State::getInstance()->setContext($backupContext);
    }

    /**
     * @test
     */
    public function shouldBeAbleToCheckDirtyFlag()
    {
        $variable = new Variable($this->guid);
        $this->assertFalse($variable->isDirty());
    }

    /**
     * @test
     * @expectedException \Sakwa\Exception
     */
    public function shouldNotBeAllowedToCallSetVariableValue()
    {
        $variable = new Variable($this->guid);
        $variable->setVariableValue(new Guid(), 42);
    }

    /**
     * @test
     * @expectedException \Sakwa\Exception
     */
    public function shouldNotBeAllowedToCallMethod()
    {
        $variable = new Variable($this->guid);
        $variable->callMethod('foo');
    }

    /**
     * @test
     * @expectedException \Sakwa\Exception
     */
    public function shouldNotBeAllowedToCallExecuteDeferredMethodCalls()
    {
        $variable = new Variable($this->guid);
        $variable->executeDeferredMethodCalls();
    }

    /**
     * @test
     */
    public function shouldBeAbleToReInitializeVariable()
    {
        $this->decisionModel->setInitializeMode(InitializeMode::SessionStart);
        $variable = new Variable($this->guid);

        $this->setUp();
        $this->decisionModel->setInitializeMode(InitializeMode::SessionStartDefaultValue);
        $this->decisionModel->setValue('123');
        $variable = new Variable($this->guid);
        $this->assertEquals('123', $variable->getValue());

        $this->setUp();
        $variable = new VariableTestObject($this->guid);
        $this->assertFalse($variable->isReInitialize);
        $variable->reInitialize();
        $this->assertTrue($variable->isReInitialize);
    }
}

class VariableTestObject extends Variable
{
    public $isReInitialize = false;

    public function getCycleContext()
    {
        return $this->cycleContext;
    }

    protected function initializeVariableState($isReInitialize = false)
    {
        $this->isReInitialize = $isReInitialize;
    }
}