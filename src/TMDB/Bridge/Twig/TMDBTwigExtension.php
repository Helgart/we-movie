<?php

namespace App\TMDB\Bridge\Twig;

use App\TMDB\Bridge\Twig\Runtime\TMDBClientRuntimeExtension;
use App\TMDB\Model\Movie;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class TMDBTwigExtension extends AbstractExtension
{
    private string $posterURL;

    public function __construct(string $posterURL)
    {
        $this->posterURL = $posterURL;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('tmdb_genders', [ TMDBClientRuntimeExtension::class, 'listGenders' ]),
            new TwigFunction('tmdb_video_for_movie', [ TMDBClientRuntimeExtension::class, 'findVideoForMovie' ]),
            new TwigFunction('tmdb_image_for_movie', [ $this, 'findImageForMovie' ])
        ];
    }

    public function findImageForMovie(Movie $movie)
    {
        return $this->posterURL . '/t/p/w200' . $movie->posterPath;
    }
}