<?php

namespace App\TMDB\Bridge\Twig\Runtime;

use App\TMDB\Client;
use App\TMDB\Model\Movie;
use App\Videos\Exception\NotSupportedException;
use App\Videos\URLGenerator;
use Twig\Extension\RuntimeExtensionInterface;

final class TMDBClientRuntimeExtension implements RuntimeExtensionInterface
{
    private Client $client;
    private URLGenerator $URLGenerator;

    public function __construct(
        Client $client,
        URLGenerator $URLGenerator
    ) {
        $this->client = $client;
        $this->URLGenerator = $URLGenerator;
    }

    public function listGenders(): array
    {
        return $this->client->listGenders();
    }

    public function findVideoForMovie(Movie $movie): string
    {
        try {
            return $this->URLGenerator->generate(
                $this->client->findVideoForMovie($movie->id)
            );
        } catch (NotSupportedException $e) {}

        return '';
    }
}