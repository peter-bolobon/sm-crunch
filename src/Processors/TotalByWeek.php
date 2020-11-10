<?php

namespace SmCrunch\Processors;

use SmCrunch\ProcessorInterface;
use SmCrunch\Counters\SumCounter;

class TotalByWeek implements ProcessorInterface {

    /**
     * @var SumCounter[]
     */
    protected array $counters = [];

    public function process( array $posts ): void {
        foreach ( $posts as $post ) {
            $weekNo = $post->createdAt->format( 'W' );
            if ( !isset( $this->counters[$weekNo])) {
                $this->counters[$weekNo] = new SumCounter();
            }

            $this->counters[$weekNo]->add( 1 );
        }
    }

    public function report() {
        $report = [];
        foreach ( $this->counters as $weekNo => $counter ) {
            $report[$weekNo] = $counter->sum;
        }

        ksort( $report, SORT_NUMERIC );

        return $report;
    }

}
