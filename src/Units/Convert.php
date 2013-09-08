<?php

namespace Units;

class Convert
{
    /**
     * @var Unit[] Keyed by abbreviation
     */
    protected $units = array();

    /**
     * Matrix of conversions
     * @var array $conversions[$from][$to] = Conversion
     */
    protected $conversions = array();

    public function convert($from, $to, $value)
    {
        $from = $this->getUnit($from);
        $to = $this->getUnit($to);

        if (!$from || !$to) {
            throw new \InvalidArgumentException("A unit was invalid");
        }

        if ($from == $to) {
            return $value;
        }

        // Find conversion graph sequence
        $conversions = array();
        if ($this->hasDirectConversion($from, $to)) {
            $conversions[] = $this->getDirectConversion($from, $to);
        } else {
            $conversions = $this->getIndirectConversion($from, $to);
        }

        if (count($conversions) == 0) {
            return null;
        }

        // Convert each in chain
        foreach ($conversions as $conv) {
            $value = $conv->convert($value);
        }

        return $value;
    }

    public function register(Unit $unit)
    {
        $this->units[$unit->getAbbr()] = $unit;
    }

    public function conversion($from, $to, $modifier)
    {
        $from = $this->getUnit($from);
        $to = $this->getUnit($to);

        if (!$from || !$to) {
            throw new \InvalidArgumentException("A unit was invalid");
        }

        $conversion = new Conversion($from, $to, $modifier);

        if (!array_key_exists($from->getAbbr(), $this->conversions)) {
            $this->conversions[$from->getAbbr()] = array();
        }

        $this->conversions[$from->getAbbr()][$to->getAbbr()] = $conversion;

        // If the conversion is reversable, and the inverse hasn't been defined, add it
        if ($conversion->isReversable() && !$this->hasDirectConversion($to, $from)) {
            $this->conversions[$to->getAbbr()][$from->getAbbr()] = $conversion->getReverseConversion();
        }
    }

    public function hasUnit($unit)
    {
        if ($unit instanceof \Units\Unit) {
            return array_key_exists($unit->getAbbr(), $this->units);
        } else {
            return array_key_exists($unit, $this->units);
        }
    }

    protected function getUnit($unit)
    {
        if (is_string($unit)) {
            if (array_key_exists($unit, $this->units)) {
                $unit = $this->units[$unit];
            } else {
                return false;
            }
        }

        if (!($unit instanceof \Units\Unit)) {
            return false;
        }

        // If we don't already have the unit, register it
        if ($unit instanceof \Units\Unit && !array_key_exists($unit->getAbbr(), $this->units)) {
            $this->register($unit);
        }

        return $unit;
    }

    protected function getDirectConversion(Unit $from, Unit $to)
    {
        if (!array_key_exists($from->getAbbr(), $this->conversions)) {
            return false;
        }

        if (!array_key_exists($to->getAbbr(), $this->conversions[$from->getAbbr()])) {
            return false;
        }

        return $this->conversions[$from->getAbbr()][$to->getAbbr()];
    }

    public function hasDirectConversion($from, $to)
    {
        $from = $this->getUnit($from);
        $to = $this->getUnit($to);

        if (!$from || !$to) {
            throw new \InvalidArgumentException("A unit was invalid");
        }

        return false !== $this->getDirectConversion($from, $to);
    }

    /**
     * Convert over a number of hops if possible
     *
     * Breadth-first search from http://www.sitepoint.com/data-structures-4/
     *
     * @return SplDoublyLinkedList|array
     */
    protected function getIndirectConversion(Unit $from, Unit $to)
    {
        $origin = $from->getAbbr();
        $destination = $to->getAbbr();

        // Mark all nodes as unvisited
        $visited = array();
        foreach ($this->conversions as $vertex => $adj) {
            $visited[$vertex] = false;
        }

        // Create a queue
        $q = new \SplQueue();

        // Enqueue the origin vertex and mark as visited
        $q->enqueue($origin);
        $visited[$origin] = true;

        // Create a path that can be back-tracked
        $path = array();
        $path[$origin] = new \SplDoublyLinkedList();
        $path[$origin]->setIteratorMode(
            \SplDoublyLinkedList::IT_MODE_FIFO | \SplDoublyLinkedList::IT_MODE_KEEP
        );

        $found = false;
        while (!$q->isEmpty() && $q->bottom() != $destination) {
            $t = $q->dequeue();

            if (!empty($this->conversions[$t])) {
                // For each adjacent neighbour,
                foreach ($this->conversions[$t] as $vertex => $conv) {
                    if (!array_key_exists($vertex, $visited) || !$visited[$vertex]) {
                        // Mark as visited and enqueue
                        $q->enqueue($vertex);
                        $visited[$vertex] = true;

                        // Add to current path
                        $path[$vertex] = clone $path[$t];
                        $path[$vertex]->push($conv);
                    }
                }
            }
        }

        if (isset($path[$destination])) {
            return $path[$destination];
        } else {
            return array();
        }
    }

    public function hasIndirectConversion($from, $to)
    {
        $from = $this->getUnit($from);
        $to = $this->getUnit($to);

        if (!$from || !$to) {
            throw new \InvalidArgumentException("A unit was invalid");
        }

        return count($this->getIndirectConversion($from, $to)) > 0;
    }
}