<?php

class ControllerTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var \Sakwa\Expression\Runner\Evaluation
     */
    protected $sit;

    /**
     * @test
     */
    public function shouldCreateNewInstance()
    {
        $cacheController = \Sakwa\Cache\Controller::getInstance();
        $this->assertInstanceOf('\Sakwa\Cache\Controller', $cacheController);
    }

    /**
     * @test
     */
    public function shouldCacheValueCorrectly()
    {
        $cacheController = \Sakwa\Cache\Controller::getInstance();
        $cacheController->set('foo', 'bar');

        $this->assertTrue($cacheController->has('foo'));
        $this->assertEquals('bar', $cacheController->get('foo'));
    }
}