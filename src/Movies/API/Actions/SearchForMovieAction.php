<?php

namespace App\Movies\API\Actions;

use App\Kernel\Actions\SingleActionControllerInterface;
use App\TMDB\ClientInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class SearchForMovieAction implements SingleActionControllerInterface
{
    private ClientInterface $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
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