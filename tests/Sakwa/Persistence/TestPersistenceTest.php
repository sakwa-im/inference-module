<?php

use Sakwa\Persistence\TestPersistence;

class TestPersistenceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToCheckExistenceOfFields()
    {
        $data = array(array('class-type'      => 'sakwa.IRootNodeImpl',
                            'reference'       => '99fd2891-ea1e-46b4-9dfa-69645534ee9d',
                            'count'           => 0,
                            'node-type'       => 'Root',
                            'name'            => 'Tiny-model',
                            'description'     => '',
                            'domain-template' => 'templates\Domain template.sdt'));

        $testedClass = new TestPersistence($data);

        $this->assertTrue($testedClass->open());
        $this->assertNotNull($testedClass->getFileVersion());
        $this->assertTrue($testedClass->nextRecord());
        $this->assertTrue($testedClass->hasField('count'));
        $this->assertFalse($testedClass->hasField('doesnotExist'));

        $this->assertFalse($testedClass->nextRecord());
        $this->assertFalse($testedClass->hasField('count'));
    }
}