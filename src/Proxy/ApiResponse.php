<?php

namespace SmCrunch\Proxy;

/**
 * Describes a raw response from the SM API.
 */
class ApiResponse {

    /**
     * HTTP response code.
     *
     * Null if cURL failed to execute the request.
     */
    public ?int $status = null;

    /**
     * Raw JSON-decoded response body.
     *
     * Null if cURL failed to execute the request.
     */
    public ?object $body = null;

    /**
     * Error number as reported by cURL.
     *
     * Not null if cURL failed to execute the request.
     */
    public ?int $curlErrNo = null;

    /**
     * Human-readable cURL error message.
     *
     * Not null if cURL failed to execute the request.
     */
    public ?string $curlError = null;

}
