<?php

namespace App\TMDB\Bridge\Twig\Runtime;

use App\TMDB\ClientInterface;
use App\TMDB\Model\Movie;
use App\Videos\Exception\NotSupportedException;
use App\Videos\URLGeneratorInterface;
use Twig\Extension\RuntimeExtensionInterface;

final class TMDBClientRuntimeExtension implements RuntimeExtensionInterface
{
    private ClientInterface $client;
    private URLGeneratorInterface $URLGenerator;

    public function __construct(
        ClientInterface $client,
        URLGeneratorInterface $URLGenerator
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