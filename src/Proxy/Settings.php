<?php

namespace SmCrunch\Proxy;

/**
 * Describes SM API connection settings.
 */
class Settings {

    /**
     * Path to the CA bundle.
     *
     * If the CA bundle is not specified, the default CA bundle is used, if any.
     */
    public ?string $caPath = null;

}
