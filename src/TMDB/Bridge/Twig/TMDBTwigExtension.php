<?php

namespace App\TMDB\Bridge\Twig;

use App\TMDB\Bridge\Twig\Runtime\TMDBClientRuntimeExtension;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class TMDBTwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('tmdb_genders', [ TMDBClientRuntimeExtension::class, 'listGenders' ]),
            new TwigFunction('tmdb_video_for_movie', [ TMDBClientRuntimeExtension::class, 'findVideoForMovie' ])
        ];
    }
}