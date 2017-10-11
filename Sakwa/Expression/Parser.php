<?php

namespace Sakwa\Expression;

use Sakwa\Expression\Parser\Expression;
use Sakwa\Expression\Parser\Element;

class Parser
{
    /**
     * @var \Sakwa\Expression\Parser\Expression $expression
     */
    protected $expression;

    /**
     * @var \Sakwa\Expression\Parser\Element $element
     */
    protected $element;

    public function __construct()
    {
        $this->element = new Element('Root', '');
    }

    /**
     * Function for setting the expression
     * @param string $expression
     */
    public function setExpression($expression)
    {
        $this->expression = new Expression($expression);
    }

    /**
     * @return string
     */
    public function getExpression()
    {
        return $this->expression->getExpression();
    }

    /**
     * @param \Sakwa\Expression\Parser\Element|null $element
     */
    public function parseExpression(\Sakwa\Expression\Parser\Element $element = null)
    {
        $token = '';

        if (is_null($element)) {
            $this->element = new Element(Expression::TOKEN_ROOT, '');
            $element = $this->element;
        }

        foreach ($this->expression->getCharacter() as $character) {
            if ($this->expression->isNewToken()) {
                if ($token != '') {
                    $element->addChildElement(new Element($this->expression->getPreviousTokenType(), $token));
                    $token = '';
                }

                if ($this->expression->getCurrentTokenType() == Expression::TOKEN_GROUP) {
                    if ($character == ')') {
                        return;
                    }

                    $childElement = new Element($this->expression->getCurrentTokenType(), $character);

                    $element->addChildElement($childElement);
                    $this->parseExpression($childElement);
                    continue;
                }
            }
            $token .= $character;
        }

        if ($token != '') {
            $childElement = new Element($this->expression->getCurrentTokenType(), $token);

            $element->addChildElement($childElement);
        }
    }

    /**
     * Function for getting the root element for the parsed expression
     * @return \Sakwa\Expression\Parser\Element
     */
    public function getElement()
    {
        return $this->element;
    }
}