<?php

namespace mrmojorising\ImageProxy;

/**
 * TODO: Class documentation
 */
class ImageProxy
{
    public string $serverHost;
    public string $protocol;
    public ?string $key = null;
    public ?string $salt = null;
    public bool $secure = false;

    /**
     * @param string $serverHost
     * @param string $protocol
     * @param string|null $key
     * @param string|null $salt
     */
    public function __construct(
        string $serverHost,
        string $protocol,
        string $key = null,
        string $salt = null
    ) {
        $this->serverHost = $serverHost;
        $this->protocol = $protocol;
        if (strlen($this->key) > 0 && strlen($this->salt) > 0) {
            $this->key = $key;
            $this->salt = $salt;
            $this->secure = true;
        }
    }
}