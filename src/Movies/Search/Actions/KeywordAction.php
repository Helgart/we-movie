<?php

namespace App\Movies\Search\Actions;

use App\Kernel\Actions\SingleActionControllerInterface;
use App\TMDB\ClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class KeywordAction implements SingleActionControllerInterface
{
    private Environment $template;
    private ClientInterface $client;

    public function __construct(Environment $template, ClientInterface $client)
    {
        $this->template = $template;
        $this->client = $client;
    }

    /**
     * @Route("/movie/search", name="keyword.movies", methods={"GET"})
     */
    public function __invoke(Request $request): Response
    {
        return new Response(
            $this->template->render(
                'actions/movies/search.html.twig',
                [
                    'moviesCollection' => $this->client->findMovieByQuery(
                        $request->query->get('query', 1),
                        $request->query->get('page', 1)
                    )
                ]
            )
        );
    }
}