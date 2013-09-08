<?php

namespace Units;

class Conversion
{
    protected static $reverseOps = array(
        '+' => '-',
        '-' => '+',
        '*' => '/',
        '/' => '*',
    );

    protected $from;
    protected $to;
    protected $modifier;
    protected $operator;
    protected $reverse = false;

    public function __construct(Unit $from, Unit $to, $modifier)
    {
        $this->from = $from;
        $this->to = $to;
        $this->parseModifier($modifier);
    }

    protected function parseModifier($modifier)
    {
        if (is_array($modifier)) {
            assert(count($modifier) == 2);

            $operator = array_shift($modifier);
            $modifier = array_shift($modifier);

            assert(array_key_exists($operator, static::$reverseOps));

            $this->operator = $operator;
            $this->modifier = $modifier;
        } elseif (is_callable($modifier)) {
            $this->modifier = $modifier;
        } else {
            // Should be a scalar value - int, float
            $this->modifier = $modifier;
            $this->operator = '*';
        }
    }

    /**
     * @return \Units\Unit
     */
    public function from()
    {
        return $this->from;
    }

    /**
     * @return \Units\Unit
     */
    public function to()
    {
        return $this->to;
    }

    /**
     * Apply a conversion to a value
     * @return mixed The converted value
     */
    public function convert($value, $reverse=null)
    {
        $reverse = is_null($reverse) ? $this->reverse : $reverse;

        if (is_callable($this->modifier)) {
            return call_user_func($this->modifier, $value);
        } else {
            $operator = $reverse ? static::$reverseOps[$this->operator] : $this->operator;
            switch ($operator) {
                case '+': return $value + $this->modifier;
                case '-': return $value - $this->modifier;
                case '*': return $value * $this->modifier;
                case '/': return $value / $this->modifier;
            }

            return false;
        }
    }

    public function convertBack($value)
    {
        if ($this->isReversable()) {
            return $this->convert($value, true);
        }

        return false;
    }

    public function isReversable()
    {
        return !is_callable($this->modifier);
    }

    public function reverseByDefault()
    {
        if ($this->isReversable()) {
            $this->reverse = true;
        }
    }

    public function getReverseConversion()
    {
        $rev = clone $this;
        $rev->reverseByDefault();
        return $rev;
    }
}