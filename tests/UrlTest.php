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
}