<?php

namespace App\Videos;

use App\TMDB\Model\Video;
use App\Videos\Exception\NotSupportedException;

final class URLGenerator
{
    private array $generators = [];

    public function __construct(iterable $generators)
    {
        foreach ($generators as $generator) {
            $this->generators[] = $generator;
        }
    }

    public function generate(Video $video): string
    {
        foreach ($this->generators as $generator) {
            if ($generator->supports($video)) {
                return $generator->generate($video);
            }
        }

        throw new NotSupportedException(sprintf('Site \'%s\' is not supported', $video->site));
    }
}