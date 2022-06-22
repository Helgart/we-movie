<?php

namespace App\Videos\Generators;

use App\TMDB\Model\Video;

final class YoutubeURLGenerator extends AbstractURLGenerator
{
    public static $identifier = 'YouTube';

    public function generate(Video $video): string
    {
        return sprintf('https://www.youtube.com/embed/%s', $video->key);
    }
}