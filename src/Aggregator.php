<?php

namespace SmCrunch;

/**
 * Aggregator provides a facility to do batch processing of SM posts and report statistics.
 */
class Aggregator {

    /**
     * @var ProcessorInterface[]
     */
    protected array $processors = [];

    /**
     * Adds a processor for post processing.
     *
     * @param string $id Processor identifier.
     * @param ProcessorInterface $processor
     */
    public function addProcessor( string $id, ProcessorInterface $processor ): void {
        $this->processors[$id] = $processor;
    }

    /**
     * Pushes a batch of posts into enqueued processors.
     *
     * @param Post[] $posts
     */
    public function aggregate( array $posts ): void {
        foreach ( $this->processors as $processor ) {
            $processor->process( $posts );
        }
    }

    /**
     * Builds a report with aggregate data from processors.
     *
     * @return array
     */
    public function report(): array {
        $report = [];
        foreach ( $this->processors as $id => $processor ) {
            $report[$id] = $processor->report();
        }

        return $report;
    }

}
