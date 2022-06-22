<?php

namespace App\Movies\Twig;

use App\TMDB\Model\Movie;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MovieTwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('formatMovieTitle', [ $this, 'formatMovieTitle' ], ['is_safe' => ['html']])
        ];
    }

    public function formatMovieTitle(Movie $movie)
    {
        return $movie->title !== $movie->originalTitle ?
            sprintf('%s <em>(%s)</em>', $movie->title, $movie->originalTitle) :
            $movie->title
        ;
    }
}