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

    public function testReturnSameImage()
    {
        $this->assertEquals(
            'http://localhost:8080/insecure/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlLnBuZw==',
            (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrl('https://example.com/example.png')
                ->generate()
        );
    }

    public function testUrlWithResize()
    {
        $this->assertEquals(
            'http://localhost:8080/insecure/rt:fill/w:200/h:300/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlLnBuZw==',
            (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrl('https://example.com/example.png')
                ->resize([
                    'width' => 200,
                    'height' => 300,
                    'resizingType' => 'fill',
                ])->generate()
        );
    }

    public function testWithMultipleUrlsUsingSetImageUrl()
    {
        $this->assertEquals(
            [
                'https://example.com/example.png' => 'http://localhost:8080/insecure/rt:fill/w:200/h:300/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlLnBuZw==',
                'https://example.com/example1.png' => 'http://localhost:8080/insecure/rt:fill/w:200/h:300/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
            ],
            (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrl('https://example.com/example.png')
                ->setImageUrl('https://example.com/example1.png')
                ->resize([
                    'width' => 200,
                    'height' => 300,
                    'resizingType' => 'fill',
                ])->generate()
        );
    }

    public function testWithMultipleUrlsUsingSetImageUrls()
    {
        $this->assertEquals(
            [
                'https://example.com/example.png' => 'http://localhost:8080/insecure/rt:fill/w:200/h:300/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlLnBuZw==',
                'https://example.com/example1.png' => 'http://localhost:8080/insecure/rt:fill/w:200/h:300/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
                'https://example.com/example2.png' => 'http://localhost:8080/insecure/rt:fill/w:200/h:300/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMi5wbmc=',
            ],
            (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrls([
                    'https://example.com/example.png',
                    'https://example.com/example1.png',
                    'https://example.com/example2.png',
                ])->resize([
                    'width' => 200,
                    'height' => 300,
                    'resizingType' => 'fill',
                ])->generate()
        );
    }

    public function testAllResizingOptions()
    {
        $this->assertEquals(
            [
                'https://example.com/example.png' => 'http://localhost:8080/insecure/rt:fit/w:300/h:300/ex:1/el:1/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlLnBuZw==',
                'https://example.com/example1.png' => 'http://localhost:8080/insecure/rt:fit/w:300/h:300/ex:1/el:1/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
                'https://example.com/example2.png' => 'http://localhost:8080/insecure/rt:fit/w:300/h:300/ex:1/el:1/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMi5wbmc=',
            ],
            (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrls([
                    'https://example.com/example.png',
                    'https://example.com/example1.png',
                    'https://example.com/example2.png',
                ])->resize([
                    'width' => 300,
                    'height' => 300,
                    'resizingType' => 'fit',
                    'enlarge' => true,
                    'extend' => true,
                ])->generate()
        );
    }

    public function testSizeOptions()
    {
        $this->assertEquals(
            [
                'https://example.com/example.png' => 'http://localhost:8080/insecure/w:500/h:500/el:t/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlLnBuZw==',
                'https://example.com/example1.png' => 'http://localhost:8080/insecure/w:500/h:500/el:t/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
                'https://example.com/example2.png' => 'http://localhost:8080/insecure/w:500/h:500/el:t/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMi5wbmc=',
            ],
            (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrls([
                    'https://example.com/example.png',
                    'https://example.com/example1.png',
                    'https://example.com/example2.png',
                ])->size([
                    'width' => 500,
                    'height' => 500,
                    'enlarge' => 't', // valid values are 1, '1', 't', 'true' or boolean true
                    'extend' => 'g', // should ignore as valid values are 1, '1', 't', 'true' or boolean true
                ])->generate()
        );
    }
}