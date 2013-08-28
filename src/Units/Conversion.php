<?php

namespace Units;

class Conversion
{
    protected $from;
    protected $to;
    protected $modifier;

    public function __construct(Unit $from, Unit $to, $modifier)
    {
        $this->from = $from;
        $this->to = $to;
        $this->modifier = $modifier;
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
    public function convert($value)
    {
        if (is_callable($this->modifier)) {
            return call_user_func($this->modifier, $value);
        } else {
            return $value * $this->modifier;
        }
    }
}