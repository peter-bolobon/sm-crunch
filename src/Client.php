<?php

namespace SmCrunch;

use Exception;
use SmCrunch\Proxy\Proxy;

/**
 * Provides a SM API proxy.
 */
class Client {

    protected const RESOURCE_POSTS = '/posts';

    protected Proxy $proxy;

    public function __construct( Proxy $proxy ) {
        $this->proxy = $proxy;
    }

    /**
     * Retrieves a collection of posts at the specified page offset.
     *
     * Returns an empty collection if the page number is out of bounds.
     *
     * @param int $page
     *
     * @return Post[]
     * @throws Exception
     */
    public function retrievePosts( int $page = 1 ): array {
        $params = [ 'page' => $page ];
        $raw = $this->proxy->get( static::RESOURCE_POSTS, $params );
        $resp = RetrievePostsResponse::fromRaw( $raw );

        // NB: Page number mismatch means we're out of bounds.
        if ( $resp->page !== $page ) {
            return [];
        }

        return $resp->posts;
    }

}
