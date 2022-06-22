<?php

namespace App\Videos\Actions;

use App\Kernel\Actions\SingleActionControllerInterface;
use App\TMDB\Client;
use App\Videos\Exception\NotSupportedException;
use App\Videos\URLGenerator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class GetVideoURLAction implements SingleActionControllerInterface
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

    /**
     * @Route("/api/video/{movieId}", name="video.api.findForMovie", methods={"GET"})
     */
    public function __invoke(int $movieId): JsonResponse
    {
        try {
            return new JsonResponse([
                'success' => true,
                'link' => $this->URLGenerator->generate(
                    $this->client->findVideoForMovie($movieId)
                )
            ]);
        } catch (NotSupportedException $e) {
            return new JsonResponse([
                'success' => false,
                'error' => 'This video provider is not supported yet'
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Something happened during search for video'
            ]);
        }
    }
}