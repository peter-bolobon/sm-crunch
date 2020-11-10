<?php

namespace SmCrunch\Proxy;

use Exception;

/**
 * Provides a lower-level proxy for the SM API endpoints.
 */
class Proxy {

    protected const AUTH_ENDPOINT = '/register';

    /**
     * Base URL of the SM API.
     *
     * All endpoints are relative to the base URL, e.g. /posts.
     */
    protected string $baseUrl;

    protected Credentials $credentials;

    protected Settings $settings;

    /**
     * Transient storage for the authentication token.
     */
    protected ?string $token = null;

    public function __construct( string $baseUrl, Credentials $credentials, Settings $settings = null ) {
        $this->baseUrl = $baseUrl;
        $this->credentials = $credentials;

        if ( $settings !== null ) {
            $this->settings = $settings;
        } else {
            $this->settings = new Settings();
        }
    }

    /**
     * Retrieves an API resource using GET.
     *
     * @param string $resource
     * @param array $params
     *
     * @return object
     * @throws Exception
     */
    public function get( string $resource, array $params = [] ) {
        $target = $this->baseUrl . $resource;

        $params['sl_token'] = $this->getToken();

        $target .= '?' . http_build_query( $params );

        $c = $this->createCurlResource( $target );
        $resp = $this->executeCurl( $c );
        Proxy::throwOnError( $resp );

        return $resp->body->data;
    }

    /**
     * Returns an authentication token for the SM API.
     *
     * @return string
     * @throws Exception
     */
    protected function getToken(): string {
        if ( $this->token !== null ) {
            return $this->token;
        }

        $this->token = $this->acquireToken();

        return $this->token;
    }

    /**
     * Acquires and returns a new authentication token for the SM API.
     *
     * @return string
     * @throws Exception
     */
    protected function acquireToken(): string {
        $target = $this->baseUrl . static::AUTH_ENDPOINT;
        $c = $this->createCurlResource( $target );
        curl_setopt( $c, CURLOPT_HTTPHEADER, [ 'Content-Type: application/json' ] );
        curl_setopt( $c, CURLOPT_POST, 1 );

        $req = [
            'client_id' => $this->credentials->clientId,
            'email' => $this->credentials->email,
            'name' => $this->credentials->name,
        ];
        curl_setopt( $c, CURLOPT_POSTFIELDS, json_encode( $req ) );
        $resp = $this->executeCurl( $c );

        try {
            Proxy::throwOnError( $resp );
        } catch ( Exception $e ) {
            throw new Exception( "Failed to authenticate against SM API: {$e->getMessage()}" );
        }

        $response = RegisterResponse::fromRaw( $resp->body->data );

        return $response->token;
    }

    /**
     * Prepares a new cURL handle.
     *
     * @param string $url
     *
     * @return false|resource
     */
    protected function createCurlResource( string $url ) {
        $c = curl_init();
        curl_setopt( $c, CURLOPT_URL, $url );
        curl_setopt( $c, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $c, CURLOPT_FOLLOWLOCATION, 1 );

        if ( $this->settings->caPath !== null ) {
            curl_setopt( $c, CURLOPT_CAINFO, $this->settings->caPath );
        }

        return $c;
    }

    /**
     * Executes the cURL handle.
     *
     * @param resource $c cURL handle.
     *
     * @return ApiResponse
     */
    protected function executeCurl( $c ): ApiResponse {
        $response = curl_exec( $c );
        $errorNo = curl_errno( $c );
        $errorMsg = curl_error( $c );
        $status = curl_getinfo( $c, CURLINFO_RESPONSE_CODE );
        curl_close( $c );

        $resp = new ApiResponse();
        if ( $errorNo > 0 ) {
            $resp->curlErrNo = $errorNo;
            $resp->curlError = $errorMsg;

            return $resp;
        }

        $resp->status = $status;
        $resp->body = json_decode( $response );

        return $resp;
    }

    /**
     * Throws an exception if the response contains an error.
     *
     * @param ApiResponse $resp
     *
     * @throws Exception
     */
    protected static function throwOnError( ApiResponse $resp ) {
        if ( $resp->curlErrNo !== null ) {
            throw new Exception( "Failed to complete a request ({$resp->curlErrNo}): {$resp->curlError}" );
        }

        if ( $resp->status < 400 ) {
            return;
        }

        if ( !isset( $resp->body->error ) || !isset( $resp->body->error->message ) ) {
            throw new Exception( "Received an unspecified error from the SM API ({$resp->status})" );
        }

        throw new Exception( "Received an error from the SM API ({$resp->status}): {$resp->body->error->message}" );
    }

}
