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

    public function testDirectoryWritable()
    {
        if (method_exists($this, 'assertDirectoryIsWritable')) {
            $this->assertDirectoryIsWritable(ConfigLoader::CACHE_TEMP);
        } else {
            $isDir = is_dir(ConfigLoader::CACHE_TEMP);
            $this->assertTrue($isDir);
            $isWritable = is_writable(ConfigLoader::CACHE_TEMP);
            $this->assertTrue($isWritable);
        }
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
