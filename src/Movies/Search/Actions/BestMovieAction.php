<?php

namespace App\Movies\Search\Actions;

use App\Kernel\Actions\SingleActionControllerInterface;
use App\TMDB\ClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class BestMovieAction implements SingleActionControllerInterface
{
    private Environment $template;
    private ClientInterface $client;

    public function __construct(Environment $template, ClientInterface $client)
    {
        $this->template = $template;
        $this->client = $client;
    }

    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function __invoke(): Response
    {
        return new Response(
            $this->template->render(
                'actions/movies/best_movie.html.twig',
                [ 'movie' => $this->client->findBestMovie() ]
            )
        );
    }
}