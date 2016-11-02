<?php

use ConfiguredPresenters\BaseConfiguredTrait;
use ConfiguredPresenters\ConfigLoader;



class Testing{ 
    use BaseConfiguredTrait; 

}

class BaseConfiuredTraitTest extends PHPUnit_Framework_TestCase
{
    protected $instance;

    protected function setUp()
    {
        
        $this->instance =  new Testing();
    }

    protected function tearDown()
    {
        
    }

    public function testInit2()
    {
        
        $config = ConfigLoader::loadConfig('/aapages.latte');
        $this->instance->setConfig($config);
        $this->instance->init('AvoidTest', 'default');
        $this->assertEquals('mustavoid', $this->instance->vtid);
    }
    public function testInit()
    {
        $config = ConfigLoader::loadConfig(__DIR__ . '/_data/pages.latte');
        $this->instance->setConfig($config);
        $this->instance->init('AvoidTest', 'default');
        $this->assertEquals('mustavoid', $this->instance->vtid);
    }
    
    public function testInitSimpleVariable()
    {
        $this->instance->initVariables([
            'test' => 'value'
        ]);
        $this->isType('string')->evaluate($this->instance->test);
        $this->assertEquals('value', $this->instance->test);
    }

    public function testInitArray()
    {
        $this->instance->initVariables([
            'test' => 'value',
            'testArray' => [],
            'testInteger' => 123,
            'testNull' => null,
            'template' => null,
        ]);
        $this->isType('string')->evaluate($this->instance->test);
        $this->isType('array')->evaluate($this->instance->testArray);
        $this->isType('int')->evaluate($this->instance->testInteger);
        $this->isNull()->evaluate($this->instance->testNull);
        $this->assertObjectNotHasAttribute('template', $this->instance);
    }
}