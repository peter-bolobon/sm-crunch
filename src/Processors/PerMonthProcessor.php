<?php

namespace SmCrunch\Processors;

use SmCrunch\ProcessorInterface;

abstract class PerMonthProcessor implements ProcessorInterface {

    protected array $counters = [];

    /**
     * Reports the statistic per month.
     *
     * @return float[]|int[]
     */
    public function report() {
        $report = [];
        foreach ( $this->counters as $ymonth => $counter ) {
            $report[$ymonth] = $counter->average;
        }

        ksort( $report, SORT_NUMERIC );

        return $report;
    }

}
