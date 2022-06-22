<?php

namespace App\Movies\Search\Actions;

use App\Kernel\Actions\SingleActionControllerInterface;
use App\TMDB\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class GenderAction implements SingleActionControllerInterface
{
    private Environment $template;
    private Client $client;

    public function __construct(Environment $template, Client $client)
    {
        $this->template = $template;
        $this->client = $client;
    }

    /**
     * @Route("/{genderId}/movies", name="category.movies", methods={"GET"})
     */
    public function __invoke(string $genderId): Response
    {
        return new Response(
            $this->template->render(
                'actions/movies/gender.html.twig',
                [ 'movies' => $this->client->findMovieByGender($genderId) ]
            )
        );
    }
}