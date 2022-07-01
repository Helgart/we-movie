<?php

namespace App\Tests\Video;

use App\Videos\URLGeneratorInterface;
use Mockery;

final class URLGeneratorMockGenerator
{
    public static function getMock()
    {
        return Mockery::mock(URLGeneratorInterface::class, []);
    }
}