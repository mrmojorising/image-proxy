<?php

namespace mrmojorising\ImageProxy\tests;

use mrmojorising\ImageProxy\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    public function testPing()
    {
        $this->assertEquals('ping', Url::ping());
    }

    public function testUrlWithResize()
    {
        $this->assertEquals(
            'http://localhost:8080/insecure/rt:fill/w:200/h:300/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlLnBuZw==',
            (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))->resize([
                'width' => 200,
                'height' => 300,
                'resizingType' => 'fill',
            ])->generate('https://example.com/example.png')
        );
    }
}