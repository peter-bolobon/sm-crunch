<?php

namespace SmCrunch\Processors;

use SmCrunch\Post;
use SmCrunch\ProcessorInterface;

/**
 * Identifies the longest post in each month.
 */
class MaxLengthPerMonth implements ProcessorInterface {

    /**
     * @var Post[]
     */
    protected array $maxByMonth = [];

    /**
     * Adds a batch of posts for processing.
     *
     * @param Post[] $posts
     *
     * @return void
     */
    public function process( array $posts ): void {
        foreach ( $posts as $post ) {
            $ymonth = $post->createdAt->format( 'Ym' );
            if ( !isset( $this->maxByMonth[$ymonth])) {
                $this->maxByMonth[$ymonth] = $post;
                continue;
            }

            if ( mb_strlen( $post->message ) < mb_strlen( $this->maxByMonth[$ymonth]->message ) ) {
                continue;
            }

            $this->maxByMonth[$ymonth] = $post;
        }
    }

    /**
     * Returns the longest message for every month.
     *
     * @return Post[]
     */
    public function report() {
        $report = $this->maxByMonth;

        ksort( $report, SORT_NUMERIC );

        return $report;
    }

}
