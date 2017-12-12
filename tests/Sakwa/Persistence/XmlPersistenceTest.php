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
        $testedClass = new XmlPersistence('tests/Models/FileNotFound.sdm');
    }

    /**
     * @test
     */
    public function shouldBeAbleToLoadModel()
    {
        $persistance = new XmlPersistence('tests/Models/Tiny-expression.sdm');
        $this->assertEquals('0.0', $persistance->getFileVersion());
        $this->assertTrue($persistance->nextRecord());
        $this->assertTrue($persistance->hasField('class-type'));
        $this->assertEquals('sakwa.IRootNodeImpl', $persistance->getFieldValue('class-type'));
        $this->assertTrue($persistance->nextRecord());
    }

    /**
     * @test
     * @expectedException \Sakwa\Exception
     */
    public function shouldThrowExceptionWhenFileHasInvalidFormat()
    {
        $testedClass = new XmlPersistence('tests/Models/InValidModel.sdm');
    }
}