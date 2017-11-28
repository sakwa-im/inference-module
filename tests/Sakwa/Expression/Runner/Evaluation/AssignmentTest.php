<?php


use Sakwa\Expression\Engine;
use Sakwa\Utils\Registry;
use Sakwa\Utils\Guid;
use Sakwa\Inference\State;

class AssignmentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToAssingValuesToStateEntity()
    {
        $decisionModel = new \Sakwa\DecisionModel\VariableDef('foobar');
        $decisionModel->setGuid(new Guid('44444444-dddd-5555-eeee-666666666666'));

        Registry::set('decisionModel', $decisionModel, State::getInstance()->getContext());

        \Sakwa\Utils\Registry::getInstance(new Guid('44444444-dddd-5555-eeee-666666666666'))->addKey(new Guid('44444444-dddd-5555-eeee-666666666666'), $decisionModel);

        $em = \Sakwa\Inference\State\Manager::getInstance();
        $variable = $em->createVariable(new Guid('44444444-dddd-5555-eeee-666666666666'));

        $engine = new Engine();
        $engine->setExpression('{44444444-dddd-5555-eeee-666666666666} = 1');
        $result = $engine->processExpression();

        $this->assertEquals(1, $result->getValue());

        $engine = new Engine();
        $engine->setExpression('{44444444-dddd-5555-eeee-666666666666} + {44444444-dddd-5555-eeee-666666666666}');
        $result = $engine->processExpression();

        $this->assertEquals(2, $result->getValue());

        $tests = array(
            array('{44444444-dddd-5555-eeee-666666666666} += 2', 3),
            array('{44444444-dddd-5555-eeee-666666666666} -= 1', 2),
            array('{44444444-dddd-5555-eeee-666666666666} /= 2', 1),
            array('{44444444-dddd-5555-eeee-666666666666} *= 2', 2),
            array('{44444444-dddd-5555-eeee-666666666666} %= 2', 0)
        );

        foreach($tests as $test) {
            list($expression, $expectedResult) = $test;

            $engine = new Engine();
            $engine->setExpression($expression);
            $result = $engine->processExpression();

            $this->assertEquals($expectedResult, $result->getValue());
        }
    }
}