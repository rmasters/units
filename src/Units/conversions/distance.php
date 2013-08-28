<?php

use \Units\Facade as Units;
use \Units\Unit;

function register_distances(Convert &$converter = null) {
    if (is_null($converter)) {
        $converter = Units::getInstance();
    }

    $converter->register(new Unit('Nanometre', 'nm'));
    $converter->register(new Unit('Micrometre', 'um')); # µ (mu) isn't too easy to type :)
    $converter->register(new Unit('Milimetre', 'mm'));
    $converter->register(new Unit('Centimetre', 'cm'));
    $converter->register(new Unit('Metre', 'm'));
    $converter->register(new Unit('Kilometre', 'km'));

    $converter->register(new Unit('Mile', 'mi'));
    $converter->register(new Unit('Yard', 'yd'));
    $converter->register(new Unit('Foot', 'ft'));
    $converter->register(new Unit('Inch', 'in'));

    /* Conversion graph

    nm - mum - mm - cm - m - km
                  /    /      \
               in - ft - yd - mi

    */

    $converter->conversion('nm', 'um', function($nm) { return $nm / 1000; });
    $converter->conversion('um', 'nm', function($um) { return $um * 1000; });

    $converter->conversion('um', 'mm', function($um) { return $um / 1000; });
    $converter->conversion('mm', 'um', function($mm) { return $mm * 1000; });

    $converter->conversion('mm', 'cm', function($mm) { return $mm / 10; });
    $converter->conversion('cm', 'mm', function($cm) { return $cm * 10; });

    $converter->conversion('cm', 'm', function($mm) { return $cm / 100; });
    $converter->conversion('m', 'cm', function($m) { return $m * 100; });

    $converter->conversion('m', 'km', function($m) { return $m / 1000; });
    $converter->conversion('km', 'm', function($km) { return $km * 1000; });

    ///

    $converter->conversion('in', 'ft', function($in) { return $nm / 12; });
    $converter->conversion('ft', 'in', function($ft) { return $um * 12; });

    $converter->conversion('ft', 'yd', function($ft) { return $nm / 3; });
    $converter->conversion('yd', 'ft', function($yd) { return $um * 3; });

    $converter->conversion('yd', 'mi', function($yd) { return $nm / 1760; });
    $converter->conversion('mi', 'yd', function($mi) { return $um * 1760; });

    ///

    $converter->conversion('cm', 'in', function($cm) { return $cm * 0.393700787; });
    $converter->conversion('in', 'cm', function($in) { return $in * 2.54; });

    $converter->conversion('m', 'ft', function($m) { return $m * 3.2808399; });
    $converter->conversion('ft', 'm', function($ft) { return $ft * 0.3048; });

    $converter->conversion('km', 'mi', function($km) { return $km * 0.621371192; });
    $converter->conversion('mi', 'km', function($mi) { return $mi * 1.609344; });
}