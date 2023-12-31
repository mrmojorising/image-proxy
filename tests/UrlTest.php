<?php

namespace mrmojorising\ImageProxy\tests;

use mrmojorising\ImageProxy\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    private const EXAMPLE_URL_1 = 'https://example.com/example1.png';
    private const EXAMPLE_URL_2 = 'https://example.com/example2.png';
    private const EXAMPLE_URL_3 = 'https://example.com/example3.png';

    public function testInsecureReturnSameImage()
    {
        $this->assertEquals(
            expected: 'http://localhost:8080/insecure/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
            actual: (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrl(self::EXAMPLE_URL_1)
                ->generate()
        );
    }

    public function testInsecureUrlWithResize()
    {
        $this->assertEquals(
            expected: 'http://localhost:8080/insecure/rt:fill/w:200/h:300/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
            actual: (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrl(self::EXAMPLE_URL_1)
                ->resize([
                    'width' => 200,
                    'height' => 300,
                    'resizingType' => 'fill',
                ])->generate()
        );
    }

    public function testInsecureWithMultipleUrlsUsingSetImageUrl()
    {
        $this->assertEquals(
            expected: [
                'https://example.com/example1.png' => 'http://localhost:8080/insecure/rt:fill/w:200/h:300/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
                'https://example.com/example2.png' => 'http://localhost:8080/insecure/rt:fill/w:200/h:300/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMi5wbmc=',
            ],
            actual: (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrl(self::EXAMPLE_URL_1)
                ->setImageUrl(self::EXAMPLE_URL_2)
                ->resize([
                    'width' => 200,
                    'height' => 300,
                    'resizingType' => 'fill',
                ])->generate()
        );
    }

    public function testInsecureWithMultipleUrlsUsingSetImageUrls()
    {
        $this->assertEquals(
            expected: [
                'https://example.com/example1.png' => 'http://localhost:8080/insecure/rt:fill/w:200/h:300/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
                'https://example.com/example2.png' => 'http://localhost:8080/insecure/rt:fill/w:200/h:300/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMi5wbmc=',
                'https://example.com/example3.png' => 'http://localhost:8080/insecure/rt:fill/w:200/h:300/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMy5wbmc=',
            ],
            actual: (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrls([
                    self::EXAMPLE_URL_1,
                    self::EXAMPLE_URL_2,
                    self::EXAMPLE_URL_3,
                ])->resize([
                    'width' => 200,
                    'height' => 300,
                    'resizingType' => 'fill',
                ])->generate()
        );
    }

    public function testInsecureAllResizingOptions()
    {
        $this->assertEquals(
            expected: [
                'https://example.com/example1.png' => 'http://localhost:8080/insecure/rt:fit/w:300/h:300/ex:1/el:1/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
                'https://example.com/example2.png' => 'http://localhost:8080/insecure/rt:fit/w:300/h:300/ex:1/el:1/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMi5wbmc=',
                'https://example.com/example3.png' => 'http://localhost:8080/insecure/rt:fit/w:300/h:300/ex:1/el:1/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMy5wbmc=',
            ],
            actual: (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrls([
                    self::EXAMPLE_URL_1,
                    self::EXAMPLE_URL_2,
                    self::EXAMPLE_URL_3,
                ])->resize([
                    'width' => 300,
                    'height' => 300,
                    'resizingType' => 'fit',
                    'enlarge' => true,
                    'extend' => true,
                ])->generate()
        );
    }

    public function testInsecureSizeOptions()
    {
        $this->assertEquals(
            expected: [
                'https://example.com/example1.png' => 'http://localhost:8080/insecure/w:500/h:500/el:t/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
                'https://example.com/example2.png' => 'http://localhost:8080/insecure/w:500/h:500/el:t/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMi5wbmc=',
                'https://example.com/example3.png' => 'http://localhost:8080/insecure/w:500/h:500/el:t/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMy5wbmc=',
            ],
            actual: (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrls([
                    self::EXAMPLE_URL_1,
                    self::EXAMPLE_URL_2,
                    self::EXAMPLE_URL_3,
                ])->size([
                    'width' => 500,
                    'height' => 500,
                    'enlarge' => 't', // valid values are 1, '1', 't', 'true' or boolean true
                    'extend' => 'g', // should ignore as valid values are 1, '1', 't', 'true' or boolean true
                ])->generate()
        );
    }
}