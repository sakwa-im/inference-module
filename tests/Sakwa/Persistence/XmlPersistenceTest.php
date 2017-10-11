<?php

use Sakwa\Persistence\XmlPersistence;

class XmlPersistenceTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @test
     * @expectedException \Sakwa\Exception
     */
    public function shouldThrowExceptionWhenFileNotFound()
    {
        $testedClass = new XmlPersistence('/tmp/unknown_file_12345.xml');
    }
}