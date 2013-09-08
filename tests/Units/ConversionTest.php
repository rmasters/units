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

    public function testScalarMultiplyConversion()
    {
        $conversion = new Conversion($this->from, $this->to, array('*', 1000));

        $this->assertEquals(2000, $conversion->convert(2));
        $this->assertTrue($conversion->isReversable());
        $this->assertEquals(5, $conversion->convertBack(5000));
    }

    public function testScalarDivisionConversion()
    {
        $conversion = new Conversion($this->from, $this->to, array('/', 1000));

        $this->assertEquals(2, $conversion->convert(2000));
        $this->assertTrue($conversion->isReversable());
        $this->assertEquals(5000, $conversion->convertBack(5));
    }

    public function testScalarAdditionConversion()
    {
        $conversion = new Conversion($this->from, $this->to, array('+', 20));

        $this->assertEquals(120, $conversion->convert(100));
        $this->assertTrue($conversion->isReversable());
        $this->assertEquals(220, $conversion->convertBack(240));
    }

    public function testScalarSubtractionConversion()
    {
        $conversion = new Conversion($this->from, $this->to, array('-', 50));

        $this->assertEquals(450, $conversion->convert(500));
        $this->assertTrue($conversion->isReversable());
        $this->assertEquals(150, $conversion->convertBack(100));
    }
}