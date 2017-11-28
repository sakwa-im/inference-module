<?php

namespace Sakwa\Expression;

use Sakwa\Cache\Controller AS CacheController;

class Engine
{
    /**
     * @var string $expression
     */
    protected $expression;

    /**
     * @var \Sakwa\Expression\Parser\Element $element
     */
    protected $element;

    /**
     * @var boolean $cacheEnabled
     */
    public $cacheEnabled = true;

    /**
     * @param string $expression
     */
    public function setExpression($expression)
    {
        $this->expression = $expression;
        $cacheController = CacheController::getInstance();
        $element = null;

        if ($this->isCacheEnabled() && $cacheController->has($this->expression)) {
            $element = $cacheController->get($this->expression);
        }

        if (is_null($element)) {
            $element = $this->parseExpression($this->expression);
            $element = $this->planExpression($element);

            if ($this->isCacheEnabled()) {
                $cacheController->set($this->expression, $element);
            }
        }

        $this->element = $element;
    }

    /**
     * @return array
     */
    public function getAllEntitiesFromExpression()
    {
        if (!is_null($this->element)) {
            return $this->element->getEntities();
        }
        return array();
    }

    /**
     * Function for processing the expression to an working execution plan
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     */
    public function processExpression()
    {
        $runner = new Runner();
        $runner->setElement($this->element);
        return $runner->run();
    }

    /**
     * @param $expression
     *
     * @return \Sakwa\Expression\Parser\Element
     */
    protected function parseExpression($expression)
    {
        $parser = new Parser();
        $parser->setExpression($expression);
        $parser->parseExpression();

        return $parser->getElement();
    }

    /**
     * @param \Sakwa\Expression\Parser\Element $expression
     *
     * @return \Sakwa\Expression\Parser\Element
     */
    protected function planExpression(\Sakwa\Expression\Parser\Element $element)
    {
        $planner = new Planner();
        $planner->setElement($element);
        $planner->planExpression();

        return $planner->getElement();
    }

    /**
     * Function for disabling the caching
     */
    public function enableCaching()
    {
        $this->cacheEnabled = true;
    }

    /**
     * Function for disabling the caching
     */
    public function disableCaching()
    {
        $this->cacheEnabled = false;
    }

    /**
     * @return boolean
     */
    public function isCacheEnabled()
    {
        return $this->cacheEnabled;
    }
}