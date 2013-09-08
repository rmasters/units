<?php

namespace Units;

class ConvertTest extends \PHPUnit_Framework_TestCase
{
    private $convert;

    public function setUp()
    {
        $this->convert = new Convert;
    }

    public function testRegister()
    {
        $unit = new Unit('Kilogram', 'kg');

        $this->convert->register($unit);
        $this->assertTrue($this->convert->hasUnit($unit));
        $this->assertTrue($this->convert->hasUnit('kg'));
    }

    public function testConversion()
    {
        $from = new Unit('Kilogram', 'kg');
        $to = new Unit('Gram', 'g');

        $this->convert->conversion($from, $to, function($kg) { return $kg / 1000; });
        $this->assertTrue($this->convert->hasDirectConversion($from, $to));

        $this->assertEquals(5, $this->convert->convert($from, $to, 5000));

        // Register a conversion with bogus units
        $this->setExpectedException('InvalidArgumentException');
        $this->convert->conversion(42, 43, 10);
    }

    public function testReverseConversionCreated()
    {
        $kg = new Unit('Kilogram', 'kg');
        $g = new Unit('Gram', 'g');

        $this->convert->conversion($g, $kg, 1000);

        $this->assertTrue($this->convert->hasDirectConversion($g, $kg));
        $this->assertTrue($this->convert->hasDirectConversion($kg, $g));
    }

    public function testDirectConversions()
    {
        $from = new Unit('Kilogram', 'kg');
        $to = new Unit('Gram', 'g');
        $other = new Unit('Pound', 'lb2');

        $this->convert->conversion($from, $to, function($kg) { return $kg / 1000; });
        $this->convert->register($other);

        // Convert the wrong way (currently we only know one way)
        // Scalar units would figure this out
        $this->assertFalse($this->convert->hasDirectConversion($to, $from));

        // Convert from a known unit without a direct conversion
        $this->assertFalse($this->convert->hasDirectConversion($other, $from));

        // Convert to a known unit without a direct conversion
        $this->assertFalse($this->convert->hasDirectConversion($from, $other));
    }

    public function testDirectConvertWithBogusUnits()
    {
        // Register a conversion with bogus units
        $this->setExpectedException('InvalidArgumentException');
        $this->convert->hasDirectConversion(42, 43);
    }

    public function testDirectConvertFromKnownUnitToUnregisteredUnit()
    {
        $from = new Unit('Kilogram', 'kg');

        // Convert from a known unit to an unknown unit
        $this->setExpectedException('InvalidArgumentException');
        $this->assertFalse($this->convert->hasDirectConversion($from, 'm'));
    }

    public function testDirectConvertFromUnregisteredUnit()
    {
        $from = new Unit('Kilogram', 'kg');

        $this->setExpectedException('InvalidArgumentException');
        $this->assertFalse($this->convert->hasDirectConversion('m', $from));
    }

    public function testSameUnit()
    {
        $this->convert->register(new Unit('Kilogram', 'kg'));
        $this->assertEquals(4.4, $this->convert->convert('kg', 'kg', 4.4));
    }

    public function testIndirectConversion()
    {
        $kg = new Unit('Kilogram', 'kg');
        $g = new Unit('Gram', 'g');
        $lb = new Unit('Pound', 'lb');
        $m = new Unit('Metre', 'm');

        $this->convert->register($m);
        $this->convert->conversion($g, $kg, function($g) { return $g / 1000; });
        $this->convert->conversion($kg, $lb, function($kg) { return $kg * 2.204; });

        $this->assertTrue($this->convert->hasIndirectConversion($kg, $lb));
        $this->assertEquals(1.102, $this->convert->convert('g', 'lb', 500));

        $this->assertNull($this->convert->convert('g', 'm', 100));

        // Register a conversion with bogus units
        $this->setExpectedException('InvalidArgumentException');
        $this->convert->hasIndirectConversion(42, 43);
    }

    public function testConvertFromBogusUnits()
    {
        $from = new Unit('Kilogram', 'kg');

        $this->setExpectedException('InvalidArgumentException');
        $this->assertFalse($this->convert->convert(42, 43, 10));
    }

    public function testConvertFromUnregisteredUnit()
    {
        $from = new Unit('Kilogram', 'kg');

        $this->setExpectedException('InvalidArgumentException');
        $this->assertFalse($this->convert->convert('m', $from, 10));
    }

    public function testConvertToUnregisteredUnit()
    {
        $from = new Unit('Kilogram', 'kg');

        $this->setExpectedException('InvalidArgumentException');
        $this->assertFalse($this->convert->convert($from, 'm', 10));
    }    
}