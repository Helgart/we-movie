<?php

namespace App\Videos\Generators;

use App\TMDB\Model\Video;

abstract class AbstractURLGenerator implements URLGeneratorInterface
{
    public static $identifier = '';

    public abstract function generate(Video $video): string;

    public function supports(Video $video): bool
    {
        return static::$identifier === $video->site;
    }
}