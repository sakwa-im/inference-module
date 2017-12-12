<?php

namespace Test\Expression\Parser;

use Sakwa\Expression\Parser\Expression;

class ExpressionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToRewindTheCharacterGenerator()
    {
        $expression = new Expression('1 + 2 + 3 ');

        $expectedCharacters = array('1', '+', '2', false, '1', '+', '2', '+', '3');

        foreach ($expression->getCharacter() as $character) {
            $expectedCharacter = array_shift($expectedCharacters);

            if ($expectedCharacter === false) {
                $expression->rewind();
            } else {
                $this->assertEquals($expectedCharacter, $character);
                $this->assertEquals($character, $expression->getCurrentCharacter());
            }
        }
    }
}