## Some sort of reversability built-in. For example:

A->B with a numeric modifier = A * modifier
B->A with a numeric modifier = A / modifier

Probably needs refactoring to allow the conversion to be:

-   a closure,
-   an array like [$modifier, $value] (e.g. `['/', 100]),
-   an integer, internally converted to `['*', 100]`.

## Specific unit tests for supplied conversions. Go for big numbers - test accuracy!

Tiny (fractions), small, large and very large.

## Performance testing

Run above unit tests across 10, 20 chains.

## Code quality

Docblocks + upload an apigen somewhere. Is there an apigen host like RTD?

## New interface

    Convert::targetUnit($fromUnit, $value);
    Convert::km('m', 42);

## Unit+Value wrapper

Provides a nicer shorthand and allows for assertion-style conversions as below.
Are there any in-built interfaces in PHP for casting as a number?

    $val = UnitValue(Units::get('km'), 42);
    Convert::m($val);

