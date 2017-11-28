<?php

use Sakwa\Utils\Guid;

class GuidTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @expectedException \Sakwa\Exception
     */
    public function shoulNotBeAbleToCreateGuidObjectFromInvalidGuidString()
    {
        $guid = new Guid('abcd');
    }

    /**
     * @test
     */
    public function shouldUseComCreateGuidWhenAvailable()
    {
        \GuidTest::$staticGuid = \GuidTest::generateGUID();
        eval("function com_create_guid() {return \\GuidTest::generateGUID();}");

        $guid = new Guid();
        $this->assertEquals(\GuidTest::$staticGuid, $guid);

        \GuidTest::$staticGuid = null;

        $guid = new Guid();
        $this->assertNotEquals(\GuidTest::$staticGuid, $guid);
    }

    protected static $staticGuid;
    protected static $guidCount = 0;

    public static function generateGUID()
    {
        if (is_null(self::$staticGuid)) {
            mt_srand((microtime(true) * 100000));
            return substr(sprintf('%04X%04X-%04X-%04X-%04X-%03X%05X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 4096), ++self::$guidCount, mt_rand(0, 32768)), 0, 36);
        }
        else {
            return self::$staticGuid;
        }
    }
}
