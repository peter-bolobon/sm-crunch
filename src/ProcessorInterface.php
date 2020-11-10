<?php

namespace SmCrunch;

/**
 * Describes a posts processor interface.
 */
interface ProcessorInterface {

    /**
     * Adds a batch of posts for processing.
     *
     * @param Post[] $posts
     *
     * @return void
     */
    public function process( array $posts ): void;

    /**
     * Provides a report about processed posts.
     *
     * @return mixed
     */
    public function report();

}
