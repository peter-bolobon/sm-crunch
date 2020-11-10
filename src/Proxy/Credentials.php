<?php

namespace SmCrunch\Proxy;

/**
 * Contains details required for successful authentication against the authentication endpoint.
 */
class Credentials {

    public string $clientId;

    public string $email;

    public string $name;

    public function __construct( string $clientId, string $email, string $name ) {
        $this->clientId = $clientId;
        $this->email = $email;
        $this->name = $name;
    }

}
