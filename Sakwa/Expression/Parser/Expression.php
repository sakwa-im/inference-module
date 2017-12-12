<?php

namespace Sakwa\Expression\Parser;

use Sakwa\Expression\Parser\Element\Base;

class Expression extends Base
{
    /**
     * @var string $expression
     */
    protected $expression = '';

    /**
     * @var integer $index
     */
    protected $index = -1;

    /**
     * @var string $currentCharacter
     */
    protected $currentCharacter = null;

    /**
     * @var integer $previousTokenType
     */
    protected $previousTokenType = self::TOKEN_UNKNOWN;

    /**
     * @var integer $currentTokenType
     */
    protected $currentTokenType = self::TOKEN_UNKNOWN;

    /**
     * @var boolean $newToken
     */
    protected $newToken = false;

    /**
     * @var boolean $isEscapeCharacter
     */
    protected $isEscapeCharacter = false;

    /**
     * Expression constructor.
     *
     * @param $expression
     */
    public function __construct($expression)
    {
        $this->expression = $expression;
    }

    /**
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * Function for rewinding the generator
     */
    public function rewind()
    {
        $this->index = -1;
    }

    /**
     * Generator for getting the next character from the expression
     * @return mixed
     */
    public function getCharacter()
    {
        while (isset($this->expression[++$this->index])) {
            $this->setCurrentCharacter($this->expression[$this->index]);
            $this->inferTokenType();

            if ($this->getCurrentTokenType() != self::TOKEN_WHITESPACE) {
                yield $this->currentCharacter;
            }
        }

        if ($this->getCurrentTokenType() == self::TOKEN_WHITESPACE) {
            $this->currentTokenType = $this->getPreviousTokenType();
        }
    }

    /**
     * Function for getting the current character
     * @return string
     */
    public function getCurrentCharacter()
    {
        return $this->currentCharacter;
    }

    /**
     * Function for getting the current character
     * @return void
     */
    public function setCurrentCharacter($character)
    {
        $this->currentCharacter = $character;
    }

    /**
     * Function for checking if we have started a new token
     * @return boolean
     */
    public function isNewToken()
    {
        return $this->newToken;
    }

    /**
     * @return string
     */
    public function getCurrentTokenType()
    {
        return $this->currentTokenType;
    }

    /**
     * @return string
     */
    public function getPreviousTokenType()
    {
        return $this->previousTokenType;
    }

    /**
     * Function for infering the token type
     * @return void
     */
    protected function inferTokenType()
    {
        if (preg_match('/[0-9]/', $this->currentCharacter)) {
            $this->transitionTokenType(self::TOKEN_NUMBER);
        }
        elseif (preg_match('/[a-zA-Z]/', $this->currentCharacter)) {
            $this->transitionTokenType(self::TOKEN_IDENTIFIER);
        }
        elseif (in_array($this->currentCharacter, array('+', '-', '/', '*', '^', '%'))) {
            $this->transitionTokenType(self::TOKEN_OPERATOR);
        }
        elseif (in_array($this->currentCharacter, array('=', '!', '>', '<'))) {
            $this->transitionTokenType(self::TOKEN_LOGIC_OPERATOR);
        }
        elseif (in_array($this->currentCharacter, array(' ', "\n", "\t"))) {
            $this->transitionTokenType(self::TOKEN_WHITESPACE);
        }
        elseif ($this->currentCharacter == '"') {
            $this->transitionTokenType(self::TOKEN_LITERAL);
        }
        elseif ($this->currentCharacter == '.') {
            $this->transitionTokenType(self::TOKEN_POINT);
        }
        elseif ($this->currentCharacter == ',') {
            $this->transitionTokenType(self::TOKEN_PARAMETER_SEPARATOR);
        }
        elseif (in_array($this->currentCharacter, array('(', ')'))) {
            $this->transitionTokenType(self::TOKEN_GROUP);
        }
        elseif (in_array($this->currentCharacter, array('{', '}'))) {
            $this->transitionTokenType(self::TOKEN_VARIABLE_IDENTIFIER);
        }
        elseif ($this->currentCharacter == '\\') {
            $this->transitionTokenType(self::TOKEN_ESCAPE);
        }
    }

    /**
     * Function for validating the corrent token type transition
     * @param integer $tokenType
     * @return void
     */
    protected function transitionTokenType($tokenType)
    {
        switch ($this->currentTokenType.$tokenType) {
            case self::TRANSITION_UNKNOWN_TO_NUMBER:
            case self::TRANSITION_UNKNOWN_TO_IDENTIFIER:
            case self::TRANSITION_UNKNOWN_TO_OPERATOR:
            case self::TRANSITION_UNKNOWN_TO_LOGIC_OPERATOR:
            case self::TRANSITION_UNKNOWN_TO_WHITESPACE:
            case self::TRANSITION_UNKNOWN_TO_GROUP:
            case self::TRANSITION_UNKNOWN_TO_PARAMETER_SEPARATOR:
                /*$this->newToken = ($this->index > 0);
                $newTokenType   = $tokenType;
                break;*/

            case self::TRANSITION_NUMBER_TO_WHITESPACE:
            case self::TRANSITION_NUMBER_TO_OPERATOR:
            case self::TRANSITION_NUMBER_TO_LOGIC_OPERATOR:
            case self::TRANSITION_NUMBER_TO_GROUP:
            case self::TRANSITION_NUMBER_TO_PARAMETER_SEPARATOR:

            case self::TRANSITION_IDENTIFIER_TO_OPERATOR:
            case self::TRANSITION_IDENTIFIER_TO_LOGIC_OPERATOR:
            case self::TRANSITION_IDENTIFIER_TO_WHITESPACE:
            case self::TRANSITION_IDENTIFIER_TO_GROUP:
            case self::TRANSITION_IDENTIFIER_TO_PARAMETER_SEPARATOR:

            case self::TRANSITION_OPERATOR_TO_NUMBER:
            case self::TRANSITION_OPERATOR_TO_IDENTIFIER:
            case self::TRANSITION_OPERATOR_TO_OPERATOR:
            case self::TRANSITION_OPERATOR_TO_LOGIC_OPERATOR:
            case self::TRANSITION_OPERATOR_TO_WHITESPACE:
            case self::TRANSITION_OPERATOR_TO_GROUP:
            case self::TRANSITION_OPERATOR_TO_PARAMETER_SEPARATOR:

            case self::TRANSITION_LOGIC_OPERATOR_TO_NUMBER:
            case self::TRANSITION_LOGIC_OPERATOR_TO_IDENTIFIER:
            case self::TRANSITION_LOGIC_OPERATOR_TO_OPERATOR:
            case self::TRANSITION_LOGIC_OPERATOR_TO_WHITESPACE:
            case self::TRANSITION_LOGIC_OPERATOR_TO_GROUP:
            case self::TRANSITION_LOGIC_OPERATOR_TO_PARAMETER_SEPARATOR:

            case self::TRANSITION_WHITESPACE_TO_NUMBER:
            case self::TRANSITION_WHITESPACE_TO_IDENTIFIER:
            case self::TRANSITION_WHITESPACE_TO_OPERATOR:
            case self::TRANSITION_WHITESPACE_TO_LOGIC_OPERATOR:
            case self::TRANSITION_WHITESPACE_TO_GROUP:
            case self::TRANSITION_WHITESPACE_TO_PARAMETER_SEPARATOR:

            case self::TRANSITION_GROUP_TO_NUMBER:
            case self::TRANSITION_GROUP_TO_IDENTIFIER:
            case self::TRANSITION_GROUP_TO_OPERATOR:
            case self::TRANSITION_GROUP_TO_LOGIC_OPERATOR:
            case self::TRANSITION_GROUP_TO_WHITESPACE:
            case self::TRANSITION_GROUP_TO_GROUP:
            case self::TRANSITION_GROUP_TO_PARAMETER_SEPARATOR:

            case self::TRANSITION_PARAMETER_SEPARATOR_TO_NUMBER:
            case self::TRANSITION_PARAMETER_SEPARATOR_TO_IDENTIFIER:
            case self::TRANSITION_PARAMETER_SEPARATOR_TO_OPERATOR:
            case self::TRANSITION_PARAMETER_SEPARATOR_TO_LOGIC_OPERATOR:
            case self::TRANSITION_PARAMETER_SEPARATOR_TO_WHITESPACE:
            case self::TRANSITION_PARAMETER_SEPARATOR_TO_GROUP:
            case self::TRANSITION_PARAMETER_SEPARATOR_TO_PARAMETER_SEPARATOR:

            case self::TRANSITION_FUNCTION_CALL_TO_IDENTIFIER:
                $this->newToken = true;
                $newTokenType   = $tokenType;
                break;

            case self::TRANSITION_WHITESPACE_TO_POINT:
            case self::TRANSITION_VARIABLE_IDENTIFIER_TO_POINT:
            case self::TRANSITION_UNKNOWN_TO_POINT:
                $this->newToken = true;
                $newTokenType   = self::TOKEN_FUNCTION_CALL;
                break;

            case self::TRANSITION_VARIABLE_IDENTIFIER_TO_VARIABLE_IDENTIFIER:
                $this->setCurrentCharacter('');
                $this->newToken = true;
                $newTokenType   = self::TOKEN_UNKNOWN;
                break;

            case self::TRANSITION_GROUP_TO_LITERAL:
            case self::TRANSITION_WHITESPACE_TO_LITERAL:
            case self::TRANSITION_LOGIC_OPERATOR_TO_LITERAL:
            case self::TRANSITION_OPERATOR_TO_LITERAL:
            case self::TRANSITION_IDENTIFIER_TO_LITERAL:
            case self::TRANSITION_NUMBER_TO_LITERAL:
            case self::TRANSITION_UNKNOWN_TO_LITERAL:

            case self::TRANSITION_UNKNOWN_TO_VARIABLE_IDENTIFIER:
            case self::TRANSITION_NUMBER_TO_VARIABLE_IDENTIFIER:
            case self::TRANSITION_IDENTIFIER_TO_VARIABLE_IDENTIFIER:
            case self::TRANSITION_OPERATOR_TO_VARIABLE_IDENTIFIER:
            case self::TRANSITION_WHITESPACE_TO_VARIABLE_IDENTIFIER:
            case self::TRANSITION_GROUP_TO_VARIABLE_IDENTIFIER:
                if (isset($this->expression[++$this->index])) {
                    $this->setCurrentCharacter($this->expression[$this->index]);
                }
                $this->newToken = true;
                $newTokenType   = $tokenType;
                break;

            case self::TRANSITION_LITERAL_TO_LITERAL:
                if ($this->isEscapeCharacter) {
                    $this->newToken = false;
                    $newTokenType   = $this->currentTokenType;
                }
                else {
                    $this->setCurrentCharacter('');
                    $this->newToken = true;
                    $newTokenType   = self::TOKEN_UNKNOWN;
                }
                break;

            case self::TRANSITION_LITERAL_TO_ESCAPE:
                if (!$this->isEscapeCharacter) {
                    $this->setCurrentCharacter('');
                    $this->isEscapeCharacter = true;
                }
                else {
                    $this->isEscapeCharacter = false;
                }
                $this->newToken = false;
                $newTokenType   = $this->currentTokenType;
                break;

            case self::TRANSITION_NUMBER_TO_NUMBER:
            case self::TRANSITION_IDENTIFIER_TO_IDENTIFIER:
            case self::TRANSITION_IDENTIFIER_TO_NUMBER:
            case self::TRANSITION_WHITESPACE_TO_WHITESPACE:
            case self::TRANSITION_LOGIC_OPERATOR_TO_LOGIC_OPERATOR:
            case self::TRANSITION_FUNCTION_CALL_TO_WHITESPACE:
            case self::TRANSITION_NUMBER_TO_POINT:

            case self::TRANSITION_LITERAL_TO_NUMBER:
            case self::TRANSITION_LITERAL_TO_IDENTIFIER:
            case self::TRANSITION_LITERAL_TO_OPERATOR:
            case self::TRANSITION_LITERAL_TO_WHITESPACE:
            case self::TRANSITION_LITERAL_TO_VARIABLE_IDENTIFIER:
            case self::TRANSITION_LITERAL_TO_POINT:

            case self::TRANSITION_VARIABLE_IDENTIFIER_TO_NUMBER:
            case self::TRANSITION_VARIABLE_IDENTIFIER_TO_IDENTIFIER:
            case self::TRANSITION_VARIABLE_IDENTIFIER_TO_OPERATOR:
                $this->isEscapeCharacter = false;
                $this->newToken = false;
                $newTokenType   = $this->currentTokenType;
                break;

            // @codeCoverageIgnoreStart
            default:
                $this->newToken = true;
                $newTokenType   = self::TOKEN_UNKNOWN;
                break;
            // @codeCoverageIgnoreEnd
        }

        if ($this->newToken && $this->currentTokenType != self::TOKEN_WHITESPACE) {
            $this->previousTokenType = $this->currentTokenType;
        }
        $this->currentTokenType = $newTokenType;
    }
}