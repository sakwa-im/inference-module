<?php

namespace Sakwa\Expression\Parser\Element;

use Sakwa\Expression\Runner;
use Sakwa\Expression\Parser\Element;
use Sakwa\Expression\Runner\Functions\Factory;
use Sakwa\Expression\Runner\Evaluation\Value;

class FunctionCall extends Element
{

    /**
     * @var \Sakwa\Expression\Runner\Functions\Plugin\Base $plugin
     */
    protected $plugin = null;

    /**
     * FunctionCall constructor.
     *
     * @param \Sakwa\Expression\Parser\Element $element
     */
    public function __construct(\Sakwa\Expression\Parser\Element $element)
    {
        $this->hydrate($element);
        $this->resolvePlugin();
    }

    protected function resolvePlugin()
    {
        if (!$this->hasEntityReference() && Factory::hasPlugin($this->getToken())) {
            $this->setPlugin(Factory::getPlugin($this->getToken()));
        }
    }

    /**
     * @param \Sakwa\Expression\Runner\Functions\Plugin\Base $plugin
     */
    public function setPlugin(\Sakwa\Expression\Runner\Functions\Plugin\Base $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @return \Sakwa\Expression\Runner\Functions\Plugin\Base
     */
    public function getPlugin()
    {
        return $this->plugin;
    }

    /**
     * @return boolean
     */
    public function hasPlugin()
    {
        return !is_null($this->plugin);
    }

    /**
     * @param \Sakwa\Expression\Parser\Element $element
     */
    protected function hydrate(\Sakwa\Expression\Parser\Element $element)
    {
        $this->setToken($element->getToken());
        $this->setElementType(self::TOKEN_FUNCTION_CALL);
        $this->setEntityReference($element->getEntityReference());
        $this->setChildren($element->getChildren());
    }

    /**
     * @return null|\Sakwa\Expression\Runner\Evaluation\Value|\Sakwa\Inference\State\Entity\DomainObject\MethodCall
     */
    public function evaluate()
    {
        $parameters = array();

        foreach ($this->childElements as $childElement) {
            $runner = new Runner();
            $runner->setElement($childElement);

            $parameters[] = $runner->run();
        }

        if ($this->hasPlugin()) {
            return $this->plugin->execute(...$parameters);
        }
        else {
            $entityManager = \Sakwa\Inference\State\Manager::getInstance();
            $entity = $entityManager->getEntity($this->getEntityReference());

            //TODO: mechanisme checken op compatibiliteit met Evaluation\Value
            return $entity->callMethod($this->getToken(), $parameters);
        }
    }

    /**
     * @return null|\Sakwa\Expression\Runner\Evaluation\Value|\Sakwa\Inference\State\Entity\DomainObject\MethodCall
     */
    public function getValue()
    {
        return $this->evaluate();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $output = array();

        $token = $this->getToken();

        if($token != '.') {
            $outputToken = '';

            if ($this->hasEntityReference()) {
                $outputToken = '{'.$this->getEntityReference().'}.';
            }

            $outputToken .= $token;

            $outputChildElement = array();

            foreach ($this->getChildren() as $childNode) {
                $outputChildElement[] = $childNode->__toString();
            }

            $output[] = $outputToken.'('.implode(', ', $outputChildElement).')';
        }
        else {
            $output[] = '.';
        }

        return implode(' ', $output);
    }
}