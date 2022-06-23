<?php

namespace App\Movies\API\Actions;

use App\Kernel\Actions\SingleActionControllerInterface;
use App\TMDB\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class SearchForMovieAction implements SingleActionControllerInterface
{
    private Client $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/api/movie/search", name="movie.api.find", methods={"GET"})
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            return new JsonResponse([
                'success' => true,
                'results' => $this->client->findMovieByQuery($request->query->get('query'))
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Something happened during search for video'
            ]);
        }
    }
}