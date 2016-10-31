
<?php

use ConfiguredPresenters\CookieCounter\VisitCounter;

/**
 * test of ConfigParserTest
 *
 * @author chlad
 */
class VisitCounterTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        
    }

    protected function tearDown()
    {
        
    }

    // tests

    public function testAction()
    {
        $counter = new VisitCounter('presenter', 'action');
        $counter->countOfBeenHere();
        $this->isType("integer")->evaluate($counter->countOfBeenHere());
        $this->assertEquals(1, $counter->countOfBeenHere());
        $this->assertEquals(1, $counter->countOfBeenHere());
        $counter = new VisitCounter('presenter', 'action');
        $counter->countOfBeenHere();
        $this->isType("integer")->evaluate($counter->countOfBeenHere());
        $this->assertEquals(1, $counter->countOfBeenHere());
    }
}