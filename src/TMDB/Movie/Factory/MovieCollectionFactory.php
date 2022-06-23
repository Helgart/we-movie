<?php

namespace TMDB\Movie\Factory;

use App\TMDB\Model\MovieCollection;

class MovieCollectionFactory
{
    public static function movieCollectionFromTMDBResponse(array $movies, array $metadata): MovieCollection
    {
        return new MovieCollection(
            $metadata['page'] ?? 0,
            $metadata['total_pages'] ?? 0,
            $metadata['total_results'] ?? 0,
            $movies
        );
    }
}