<?php

namespace SmCrunch\Counters;

/**
 * Provides an iterative average calculator.
 */
class AvgCounter {

    /**
     * The current average of all added values.
     *
     * @var int|float
     */
    public $average = 0;

    /**
     * Number of values added.
     *
     * @var int
     */
    protected int $count = 0;

    /**
     * Adds a value from the series.
     *
     * @param int|float $value
     */
    public function add( $value ) {
        $nextCount = $this->count + 1;
        $nextAverage = $this->average + ( $value - $this->average ) / $nextCount;

        $this->average = $nextAverage;
        $this->count = $nextCount;
    }

}
