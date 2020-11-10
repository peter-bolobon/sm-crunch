<?php

namespace SmCrunch;

use Exception;

/**
 * Describes a response from the /posts API endpoint.
 */
class RetrievePostsResponse {

    public int $page;

    /**
     * @var Post[]
     */
    public array $posts = [];

    /**
     * @param object $o
     *
     * @return RetrievePostsResponse
     * @throws Exception
     */
    public static function fromRaw( object $o ): RetrievePostsResponse {
        $resp = new RetrievePostsResponse();
        $resp->page = $o->page;

        $posts = [];
        foreach ( $o->posts as $p ) {
            $posts[] = Post::fromRaw( $p );
        }

        $resp->posts = $posts;

        return $resp;
    }

}
