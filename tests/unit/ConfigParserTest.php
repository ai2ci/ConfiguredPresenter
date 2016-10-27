
<?php

use ConfiguredPresenters\ConfigParser;

/**
 * test of ConfigParserTest
 *
 * @author chlad
 */
class ConfigParserTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        $this->testedValue = 'value';
        $this->testedKey = 'key';
        $this->path = "1.{$this->testedKey}";
        $this->reSetUp();
    }

    protected function reSetUp()
    {
        $this->arrayFull = [
            0 => [],
            1 => [$this->testedKey => $this->testedValue],
            2 => ['_extends' => 1],
        ];
    }

    protected function tearDown()
    {
        
    }

    // tests

    public function testCallgetInheritedVariableAssert()
    {
        $this->reSetUp();
        $result = ConfigParser::getInheritedVariable(explode('.', $this->path), $this->arrayFull);
        $this->assertEquals($this->testedValue, $result);
    }

    public function testCallrecursiveResolveVariableAssert()
    {
        $this->reSetUp();
        ConfigParser::recursiveResolve($this->arrayFull, $this->arrayFull);
        $this->assertEquals($this->testedValue, $this->arrayFull[2][$this->testedKey]);
    }
}