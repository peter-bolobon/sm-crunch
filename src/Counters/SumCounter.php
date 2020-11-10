<?php

namespace SmCrunch\Counters;

/**
 * Provides an iterative sum calculator.
 */
class SumCounter {

    /**
     * @var int|float
     */
    public $sum = 0;

    /**
     * Adds a value to the sum.
     *
     * @param int|float $value
     */
    public function add( $value ) {
        $this->sum += $value;
    }

}
