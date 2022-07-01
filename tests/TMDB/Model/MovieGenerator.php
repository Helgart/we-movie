<?php

namespace App\Tests\TMDB\Model;

use App\TMDB\Model\Movie;
use Nelmio\Alice\Loader\NativeLoader;

final class MovieGenerator
{
    public static function getMovieFixtures(?int $number = null): array
    {
        $key = $number ? "movie_{1..$number}" : 'movie';

        return array_values(
            (new NativeLoader())
                ->loadData([
                    Movie::class => [
                        $key => [
                            'adult' => '<boolean()>',
                            'backdropPath' => '/backdrop/<slug()>',
                            'genreIds' => '<randomElements([ 1, 2, 3, 4, 5, 6, 7, 8, 9 ], 3)>',
                            'id' => '<randomNumber()>',
                            'originalLanguage' => '<locale()>',
                            'originalTitle' => '<word()>',
                            'overview' => '<text()>',
                            'popularity' => '<randomFloat()>',
                            'posterPath' => '/poster/<slug()>',
                            'title' => '<word()>',
                            'video' => '<boolean()>',
                            'voteAverage' => '<randomFloat()>',
                            'voteCount' => '<randomFloat()>',
                            'releaseDate' => '<date("Y-m-d")>'
                        ]
                    ]
                ])
                ->getObjects()
        );
    }
}