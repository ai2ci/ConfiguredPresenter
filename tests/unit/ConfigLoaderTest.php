<?php

use \ConfiguredPresenters\ConfigLoader;

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

    public function testloadConfigNeon()
    {
        $file = 'pages.neon';
        $result = ConfigLoader::loadConfig($file);
//        unlink($file);
        $this->isType('array')->evaluate($result);
        
        $this->assertArrayHasKey('Base', $result);
        $this->assertArrayHasKey('dummyVideoSrc', $result['Base']);
        $this->assertArrayHasKey('AvoidTest', $result);
        $this->assertArrayHasKey('default', $result['AvoidTest']);
        $this->assertArrayHasKey('vtid', $result['AvoidTest']['default']);
        $this->assertArrayHasKey('video', $result['AvoidTest']);
        $this->assertArrayHasKey('vtid', $result['AvoidTest']['video']);
    }
}