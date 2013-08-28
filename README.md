# Units [![Latest Stable Version](https://poser.pugx.org/rmasters/units/v/stable.png)](https://packagist.org/packages/rmasters/units) [![master](https://travis-ci.org/rmasters/units.png?branch=master)](https://travis-ci.org/rmasters/units) [![Coverage Status](https://coveralls.io/repos/rmasters/units/badge.png)](https://coveralls.io/r/rmasters/units) [![Dependency status](http://www.versioneye.com/user/projects/521d4308632bac72d9035c93/badge.png)](http://www.versioneye.com/user/projects/521d4308632bac72d9035c93)

Units is a small unit-of-measure conversion library written in PHP. It aims to be simple to use and extend.

## Installation

Units is installable via Composer. Add the following line to your project's composer.json under the `require` section, and run `composer update`.

    "rmasters/units": "dev-master"

Installable revisions of the package are listed at [Packagist](#) and documented on the [releases page](#).

## Usage

The conversion class can either be instantiated directly, or used as a singleton using the supplied facade class. Each Convert instance has its own registry of units and conversions.

Once units and conversions have been registered (see below), values can be converted as so:

    use Units\Facade as Units;

    Units::convert('kg', 'lb', 42); // => 92.568

A number of standard conversions are supplied with the library. These functions register with the Facade singleton instance by default, or with a Convert instance if given.

    // Defined in src/Units/conversions/
    Units\register_weights(); // Metric and imperial weights
    Units\register_distances(); // Metric and imperial distances

    // Registering with a specific Convert instance
    $convert = new Convert;
    Units\register_distances($convert);

## Extending with additional conversions/units

Units uses a graph model for converting between different units. For example, the edges (connections) in the graph below are defined conversions (`to go from unit A to B, do X`). This makes it possible to convert across a range of units without defining lots of conversions.

    mg - g - kg
               \
                lb - oz
                  \
                   st

In this graph, to convert from `mg` to `st` is possibly by performing the intermediary conversions to `g`, `kg` and `lb`, without a specific `mg->st` conversion being defined.

To define new units and conversions, use the following code (accessed using the Facade):

    use \Units\Facade as Units;
    
    // Register new units
    Units::register(new Unit('Minute', 'min'));
    Units::register(new Unit('Second', 'sec'));

    // Record some one way conversions
    Units::conversion('min', 'sec', function($min) { return $min * 60; });
    Units::conversion('sec', 'min', function($sec) { return $sec / 60; });

    // Passing a Unit instance automatically registers it, if not already registered
    Units::conversion(new Unit('Hour', 'hr'), 'min', function($hr) { return $hr * 60; });
    Units::conversion('min', 'hr', function($min) { return $min / 60; });

    Units::convert('min', 'hr', 90); // => 1.5