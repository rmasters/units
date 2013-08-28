<?php

namespace Units;

use \Units\Facade as Units;
use \Units\Unit;
use \Units\Convert;

function register_weights(Convert &$converter = null) {
    if (is_null($converter)) {
        $converter = Units::getInstance();
    }

    $converter->register(new Unit('Kilogram', 'kg'));
    $converter->register(new Unit('Gram', 'g'));
    $converter->register(new Unit('Miligram', 'mg'));

    $converter->register(new Unit('Pound', 'lb'));
    $converter->register(new Unit('Stone', 'st'));
    $converter->register(new Unit('Ounce', 'oz'));

    /* Conversion graph

    mg - g - kg - lb - oz
                     \
                       st

    */

    $converter->conversion('mg', 'g', function($mg) { return $mg / 1000; });
    $converter->conversion('g', 'mg', function($g) { return $g * 1000; });

    $converter->conversion('g', 'kg', function($g) { return $g / 1000; });
    $converter->conversion('kg', 'g', function($kg) { return $kg * 1000; });

    $converter->conversion('kg', 'lb', function($kg) { return $kg * 2.204; });
    $converter->conversion('lb', 'kg', function($lb) { return $lb * 0.453; });

    $converter->conversion('lb', 'oz', function($lb) { return $lb * 16; });
    $converter->conversion('oz', 'lb', function($oz) { return $oz / 16; });

    $converter->conversion('lb', 'st', function($lb) { return $lb / 14; });
    $converter->conversion('st', 'lb', function($st) { return $st * 14; });
}