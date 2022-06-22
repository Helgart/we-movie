<?php

namespace App\TMDB\Model;

final class Movie
{
    public bool $adult;
    public string $backdropPath;
    public array $genreIds;
    public int $id;
    public string $originalLanguage;
    public string $originalTitle;
    public string $overview;
    public ?float $popularity = null;
    public string $posterPath;
    public string $title;
    public ?bool $video = null;
    public float $voteAverage;
    public float $voteCount;
    public string $releaseDate;
}