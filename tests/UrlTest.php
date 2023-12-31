<?php

namespace mrmojorising\ImageProxy\tests;

use mrmojorising\ImageProxy\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    private const EXAMPLE_URL_1 = 'https://example.com/example1.png';
    private const EXAMPLE_URL_2 = 'https://example.com/example2.png';
    private const EXAMPLE_URL_3 = 'https://example.com/example3.png';
    private const ACTUAL_URL = 'https://www.yiiframework.com/image/testimonials/humhub.png';

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

    public function testInsecureFreetextOptions()
    {
        $this->assertEquals(
            expected: 'http://localhost:8080/insecure/w:200/h:300/rt:fill/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
            actual: (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrl(self::EXAMPLE_URL_1)
                ->freetextOptions('w:200/h:300/rt:fill')
                ->generate()
        );
    }

    public function testInsecureSingleFreetextOptions()
    {
        $this->assertEquals(
            expected: 'http://localhost:8080/insecure/w:200/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
            actual: (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrl(self::EXAMPLE_URL_1)
                ->freetextOptions('w:200')
                ->generate()
        );
    }

    public function testInsecureInvalidFreetextOptions()
    {
        $this->assertEquals(
            expected: 'http://localhost:8080/insecure/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
            actual: (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrl(self::EXAMPLE_URL_1)
                ->freetextOptions('w200/h300/rtFill') // Note: none of the freetextOptions are in the expected Url
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

    public function testInsecureResizingType()
    {
        $this->assertEquals(
            expected: [
                'https://example.com/example1.png' => 'http://localhost:8080/insecure/rt:fill-down/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
                'https://example.com/example2.png' => 'http://localhost:8080/insecure/rt:fill-down/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMi5wbmc=',
                'https://example.com/example3.png' => 'http://localhost:8080/insecure/rt:fill-down/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMy5wbmc=',
            ],
            actual: (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrls([
                    self::EXAMPLE_URL_1,
                    self::EXAMPLE_URL_2,
                    self::EXAMPLE_URL_3,
                ])->resizingType('fill-down')
                ->generate()
        );
    }

    public function testInsecureResizingTypeInvalidOption()
    {
        $this->assertEquals(
            expected: [
                'https://example.com/example1.png' => 'http://localhost:8080/insecure/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
                'https://example.com/example2.png' => 'http://localhost:8080/insecure/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMi5wbmc=',
                'https://example.com/example3.png' => 'http://localhost:8080/insecure/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMy5wbmc=',
            ],
            actual: (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrls([
                    self::EXAMPLE_URL_1,
                    self::EXAMPLE_URL_2,
                    self::EXAMPLE_URL_3,
                ])->resizingType('fill-downnnn')
                ->generate()
        );
    }

    public function testInsecureResizingTypeSetTwice()
    {
        // The last usage of resizingType should overwrite any preceding
        $this->assertEquals(
            expected: [
                'https://example.com/example1.png' => 'http://localhost:8080/insecure/rt:fit/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
                'https://example.com/example2.png' => 'http://localhost:8080/insecure/rt:fit/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMi5wbmc=',
                'https://example.com/example3.png' => 'http://localhost:8080/insecure/rt:fit/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMy5wbmc=',
            ],
            actual: (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrls([
                    self::EXAMPLE_URL_1,
                    self::EXAMPLE_URL_2,
                    self::EXAMPLE_URL_3,
                ])->resizingType('fill-down')
                ->resizingType('fit')
                ->generate()
        );
    }

    public function testInsecureHeight()
    {
        $this->assertEquals(
            expected: [
                'https://example.com/example1.png' => 'http://localhost:8080/insecure/h:400/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
                'https://example.com/example2.png' => 'http://localhost:8080/insecure/h:400/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMi5wbmc=',
                'https://example.com/example3.png' => 'http://localhost:8080/insecure/h:400/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMy5wbmc=',
            ],
            actual: (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrls([
                    self::EXAMPLE_URL_1,
                    self::EXAMPLE_URL_2,
                    self::EXAMPLE_URL_3,
                ])->height(400)
                ->generate()
        );
    }

    public function testInsecureHeightInvalidOption()
    {
        $this->assertEquals(
            expected: [
                'https://example.com/example1.png' => 'http://localhost:8080/insecure/h:380/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
                'https://example.com/example2.png' => 'http://localhost:8080/insecure/h:380/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMi5wbmc=',
                'https://example.com/example3.png' => 'http://localhost:8080/insecure/h:380/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMy5wbmc=',
            ],
            actual: (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrls([
                    self::EXAMPLE_URL_1,
                    self::EXAMPLE_URL_2,
                    self::EXAMPLE_URL_3,
                ])->height(380.8)
                ->generate()
        );
    }

    public function testInsecureHeightSetTwice()
    {
        // The last usage of height should overwrite any preceding
        $this->assertEquals(
            expected: [
                'https://example.com/example1.png' => 'http://localhost:8080/insecure/h:600/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMS5wbmc=',
                'https://example.com/example2.png' => 'http://localhost:8080/insecure/h:600/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMi5wbmc=',
                'https://example.com/example3.png' => 'http://localhost:8080/insecure/h:600/aHR0cHM6Ly9leGFtcGxlLmNvbS9leGFtcGxlMy5wbmc=',
            ],
            actual: (new Url(
                serverHost: 'localhost:8080',
                protocol: 'http'
            ))
                ->setImageUrls([
                    self::EXAMPLE_URL_1,
                    self::EXAMPLE_URL_2,
                    self::EXAMPLE_URL_3,
                ])->height(400)
                ->height(600)
                ->generate()
        );
    }
}