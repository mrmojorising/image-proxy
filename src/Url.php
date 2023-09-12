<?php

namespace mrmojorising\ImageProxy;

/**
 *
 */
class Url
{
    /**
     * @param string $imgUrl
     * @return string
     */
    public static function generate(string $imgUrl): string
    {
        return $imgUrl;
    }

    /**
     * @param array $imgUrls
     * @return array
     */
    public static function generateBatch(array $imgUrls): array
    {
        $imgProxyUrls = [];

        foreach ($imgUrls as $imgUrl) {
            $imgProxyUrls[] = self::generate($imgUrl);
        }

        return $imgProxyUrls;
    }
}