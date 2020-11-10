<?php

namespace SmCrunch\Processors;

use SmCrunch\Counters\AvgCounter;
use SmCrunch\Post;

/**
 * Calculates how many posts a user creates in a month on average.
 */
class AvgMonthlyPostsPerUser extends AvgLengthPerMonth {

    /**
     * Adds a batch of posts for processing.
     *
     * @param Post[] $posts
     *
     * @return void
     */
    public function process( array $posts ): void {
        /*
         * First loop calculates SUMs grouped by year-month and user.
         * Second loop reduces the aggregates to year-month averages.
         */

        $aggregates = [];
        foreach ( $posts as $post ) {
            $ymonth = $post->createdAt->format( 'Ym' );
            if ( !isset( $aggregates[$ymonth])) {
                $aggregates[$ymonth] = [];
            }

            if ( !isset( $aggregates[$ymonth][$post->from->id] ) ) {
                $aggregates[$ymonth][$post->from->id] = 0;
            }

            $aggregates[$ymonth][$post->from->id]++;
        }

        foreach ( $aggregates as $ymonth => $userSums ) {
            $c = new AvgCounter();
            foreach ( $userSums as $sum ) {
                $c->add( $sum );
            }

            $this->counters[$ymonth] = $c;
        }
    }

}
