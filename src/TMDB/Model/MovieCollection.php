<?php

namespace App\TMDB\Model;

class MovieCollection
{
    public int $page;
    public int $nbPages;
    public int $totalResults;
    public array $movies = [];

    public function __construct(int $page, int $nbPages, int $totalResults, array $movies)
    {
        $this->page = $page;
        $this->nbPages = $nbPages;
        $this->totalResults = $totalResults;
        $this->movies = $movies;
    }
}