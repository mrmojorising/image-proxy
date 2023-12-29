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

    public function testWithMultipleUrls()
    {
        $this->assertEquals(
            [
                'https://example.com/example.png' => 'http://localhost:8080/insecure/rt:fill/w:200/h:300/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlLnBuZw==',
                'https://example.com/example1.png' => 'http://localhost:8080/insecure/rt:fill/w:200/h:300/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
            ],
            (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))->setImageUrl('https://example.com/example.png')
                ->setImageUrl('https://example.com/example1.png')
                ->resize([
                    'width' => 200,
                    'height' => 300,
                    'resizingType' => 'fill',
                ])->generate()
        );
    }

    public function testUrlWithResize()
    {
        $this->assertEquals(
            'http://localhost:8080/insecure/rt:fill/w:200/h:300/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlLnBuZw==',
            (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))->setImageUrl('https://example.com/example.png')
                ->resize([
                'width' => 200,
                'height' => 300,
                'resizingType' => 'fill',
            ])->generate()
        );
    }
}