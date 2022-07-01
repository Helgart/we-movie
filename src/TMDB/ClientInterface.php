<?php

namespace App\TMDB;

use App\TMDB\Model\Movie;
use App\TMDB\Model\MovieCollection;
use App\TMDB\Model\Video;

interface ClientInterface
{

    public function listGenders(): array;
    public function findMovieByGender(int $genderId, int $page = 1): MovieCollection;
    public function findMovieById(int $movieId): Movie;
    public function findMovieByQuery(string $query, int $page = 1): MovieCollection;
    public function findVideoForMovie(int $movieId): Video;
    public function findBestMovie(): Movie;
}