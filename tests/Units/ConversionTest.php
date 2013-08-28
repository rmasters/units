<?php

namespace Units;

class ConversionTest extends \PHPUnit_Framework_TestCase
{
    private $from;
    private $to;

    public function setUp()
    {
        $this->from = new Unit('Metre', 'm');
        $this->to = new Unit('Kilometre', 'km');
    }

    public function testClosureModifier()
    {
        $conversion = new Conversion(
            $this->from, 
            $this->to,
            function($m) {
                return $m * 1000;
            }
        );

        $this->assertEquals($this->from, $conversion->from());
        $this->assertEquals($this->to, $conversion->to());
        $this->assertEquals(2000, $conversion->convert(2));
    }

    public function testIntegerModifier()
    {
        $conversion = new Conversion(
            $this->from, 
            $this->to,
            1000
        );

        $this->assertEquals($this->from, $conversion->from());
        $this->assertEquals($this->to, $conversion->to());
        $this->assertEquals(5000, $conversion->convert(5));
    }
}