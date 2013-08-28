<?php

namespace Units;

class UnitTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $unit = new Unit('Kilogram', 'kg');
        $this->assertEquals('Kilogram', $unit->getName());
        $this->assertEquals('kg', $unit->getAbbr());
    }

    public function testAbbreviation()
    {
        $unit = new Unit('Kilogram', 'kg');
        $this->assertEquals('kg', $unit->getAbbr());

        $unit = new Unit('Kilogram', '..kilo gram ');
        $this->assertEquals('kilogram', $unit->getAbbr());

        $unit = new Unit('Kilogram', '1kg');
        $this->assertEquals('kg', $unit->getAbbr());
    }

    public function testToString()
    {
        $unit = new Unit('Kilogram', 'kg');
        $this->assertEquals('Kilogram', (string) $unit);
    }
}