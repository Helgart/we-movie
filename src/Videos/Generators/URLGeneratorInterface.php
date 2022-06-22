<?php

namespace App\Videos\Generators;

use App\TMDB\Model\Video;

interface URLGeneratorInterface
{
    public function generate(Video $video);
    public function supports(Video $video): bool;
}