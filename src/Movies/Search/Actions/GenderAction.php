<?php

namespace App\Movies\Search\Actions;

use App\Kernel\Actions\SingleActionControllerInterface;
use App\TMDB\ClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class GenderAction implements SingleActionControllerInterface
{
    private Environment $template;
    private ClientInterface $client;

    public function __construct(Environment $template, ClientInterface $client)
    {
        $this->template = $template;
        $this->client = $client;
    }

    /**
     * @Route("/{genderId}/movies", name="category.movies", methods={"GET"})
     */
    public function __invoke(Request $request, string $genderId): Response
    {
        return new Response(
            $this->template->render(
                'actions/movies/search.html.twig',
                [ 'moviesCollection' => $this->client->findMovieByGender($genderId, $request->query->get('page', 1)) ]
            )
        );
    }
}