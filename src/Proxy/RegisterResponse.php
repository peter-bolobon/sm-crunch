<?php

namespace SmCrunch\Proxy;

/**
 * Describes a /register response from the API.
 */
class RegisterResponse {

    public string $clientId;

    public string $email;

    public string $token;

    public static function fromRaw( object $o ): RegisterResponse {
        $resp = new static();
        $resp->clientId = $o->client_id;
        $resp->email = $o->email;
        $resp->token = $o->sl_token;

        return $resp;
    }

}
