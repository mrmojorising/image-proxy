<?php

namespace mrmojorising\ImageProxy;

/**
 *
 */
class Url
{
    /** @var string */
    public string $imgProxyHost = 'http://localhost:8080'; // use imgproxy localhost default

    /**
     * @param string|null $imgProxyHost
     */
    public function __construct(string $imgProxyHost = null)
    {
        if ($imgProxyHost !== null) {
            $this->imgProxyHost = $imgProxyHost;
        }
    }

    /**
     * @param string $imgUrl
     * @return string
     */
    public function generate(string $imgUrl): string
    {
        return $imgUrl;
    }

    /**
     * @param array $imgUrls
     * @return array
     */
    public function generateBatch(array $imgUrls): array
    {
        $imgProxyUrls = [];

        foreach ($imgUrls as $imgUrl) {
            $imgProxyUrls[] = self::generate($imgUrl);
        }

        return $imgProxyUrls;
    }
}