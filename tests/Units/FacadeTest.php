<?php

namespace Units;

use Units\Facade as Units;

class FacadeTest extends \PHPUnit_Framework_TestCase
{
    public function testAccessor()
    {
        Units::conversion(
            new Unit('Kilogram', 'kg'),
            new Unit('Gram', 'g'),
            function($kg) { return $kg * 1000; }
        );
        $this->assertEquals(2000, Units::convert('kg', 'g', 2));
    }

    public function testLargeGraph()
    {
        //require_once __DIR__ . '/../../src/Units/conversions/weights.php';
        register_weights();

        $this->assertEquals(3.17376, Units::convert('mg', 'oz', 90000));
    }
}