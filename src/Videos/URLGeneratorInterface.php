<?php

namespace App\Videos;

use App\TMDB\Model\Video;

interface URLGeneratorInterface
{
    public function generate(Video $video): string;
}