<?php

namespace SmCrunch\Processors;

use SmCrunch\Counters\AvgCounter;
use SmCrunch\Post;

/**
 * Reports the average post length per month.
 */
class AvgLengthPerMonth extends PerMonthProcessor {

    /**
     * Adds a batch of posts for processing.
     *
     * @param Post[] $posts
     *
     * @return void
     */
    public function process( array $posts ): void {
        foreach ( $posts as $post ) {
            $monthId = $post->createdAt->format( 'Ym' );
            if ( !isset( $this->counters[$monthId])) {
                $this->counters[$monthId] = new AvgCounter();
            }

            $this->counters[$monthId]->add( mb_strlen( $post->message ) );
        }
    }

}
