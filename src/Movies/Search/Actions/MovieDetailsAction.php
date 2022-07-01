<?php

namespace App\Movies\Search\Actions;

use App\Kernel\Actions\SingleActionControllerInterface;
use App\TMDB\ClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class MovieDetailsAction implements SingleActionControllerInterface
{
    private Environment $template;
    private ClientInterface $client;

    /**
     * @param Environment $template
     * @param ClientInterface $client
     */
    public function __construct(
        Environment $template,
        ClientInterface $client
    ) {
        $this->template = $template;
        $this->client = $client;
    }

    /**
     * @Route("/movie/{movieId}", name="movie.details", methods={"GET"})
     */
    public function __invoke(string $movieId): Response
    {
        return new Response(
            $this->template->render(
                'actions/movies/details.html.twig',
                [ 'movie' => $this->client->findMovieById($movieId) ]
            )
        );
    }
}