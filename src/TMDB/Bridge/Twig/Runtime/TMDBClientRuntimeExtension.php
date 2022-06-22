<?php

namespace App\TMDB\Bridge\Twig\Runtime;

use App\TMDB\Client;
use Twig\Extension\RuntimeExtensionInterface;

final class TMDBClientRuntimeExtension implements RuntimeExtensionInterface
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function listGenders()
    {
        return $this->client->listGenders();
    }
}