<?php

namespace Test\Expression\Parser;

use Sakwa\Expression\Parser\Element;

class ElementTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldNotFailWhenGettingNonExistentElement()
    {
        $element = new Element(Element::TOKEN_NUMBER, 1);
        $this->assertNull($element->getEntity());
    }
}