<?php

namespace Units;

/**
 * A unit that can be converted
 * 
 * In the graph model, this is a node.
 */
class Unit
{
    protected $name;
    protected $abbr;

    /**
     * @param string $name Readable name of the unit
     * @param string $abbr Abbreviation, should be a valid PHP method name
     */
    public function __construct($name, $abbr)
    {
        $this->name = $name;
        $this->setAbbr($abbr);
    }

    /**
     * @param string $abbr Abbreviation, should be a valid PHP method name
     */
    protected function setAbbr($abbr)
    {
        // Strip non alphanumerics/underscores
        $abbr = preg_replace('/[^a-zA-Z0-9_]+/', '', $abbr);
        // Must start with a non-numeric
        $this->abbr = preg_replace('/^[1-9]/', '', $abbr);
    }

    public function getAbbr()
    {
        return $this->abbr;
    }

    public function getName()
    {
        return $this->name;
    }

    public function __toString()
    {
        return $this->name;
    }
}