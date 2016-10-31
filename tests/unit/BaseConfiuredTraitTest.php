<?php

use ConfiguredPresenters\BaseConfiguredTrait;
use ConfiguredPresenters\ConfigLoader;

class BaseConfiuredTraitTest extends PHPUnit_Framework_TestCase
{

    use BaseConfiguredTrait;

    protected function setUp()
    {
        
    }

    protected function tearDown()
    {
        
    }

    public function testInit()
    {
        $config = ConfigLoader::loadConfig('pages.latte');
        $this->setConfig($config);
        $this->init('AvoidTest', 'default');
    }
    
    public function testInitSimpleVariable()
    {
        $this->initVariables([
            'test' => 'value'
        ]);
        $this->isType('string')->evaluate($this->test);
        $this->assertEquals('value', $this->test);
    }

    public function testInitArray()
    {
        $this->initVariables([
            'test' => 'value',
            'testArray' => [],
            'testInteger' => 123,
            'testNull' => null,
            'template' => null,
        ]);
        $this->isType('string')->evaluate($this->test);
        $this->isType('array')->evaluate($this->testArray);
        $this->isType('int')->evaluate($this->testInteger);
        $this->isNull()->evaluate($this->testNull);
        $this->assertObjectNotHasAttribute('template', $this);
    }
}