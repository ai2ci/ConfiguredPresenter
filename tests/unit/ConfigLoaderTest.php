<?php

use ConfiguredPresenters\ConfigLoader;

/**
 * test of ConfigParserTest
 *
 * @author chlad
 */
class ConfigLoaderTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        
    }

    protected function tearDown()
    {
        
    }

    // tests

    public function testDirectoryExists()
    {
        $isDir = is_dir(ConfigLoader::CACHE_TEMP);
        $this->assertTrue($isDir);
    }

    public function testDirectoryWritable()
    {
        $this->assertIsWritable(ConfigLoader::CACHE_TEMP);
    }

    public function testloadConfig()
    {
        $file = 'page.neon';
        touch($file);
        $result = ConfigLoader::loadConfig($file);
        unlink($file);
        $this->isType('array')->evaluate($result);
    }

}
