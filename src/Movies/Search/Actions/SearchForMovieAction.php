<?php

namespace App\Movies\Search\Actions;

use App\Kernel\Actions\SingleActionControllerInterface;
use App\tmdb\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class SearchForMovieAction implements SingleActionControllerInterface
{
    protected Environment $template;

    public function __construct(Environment $template)
    {
        $this->template = $template;
    }

    /**
     * @Route("/", {"GET"})
     */
    public function __invoke(Client $client): Response
    {
        $genders = $client->listGenders();

        return new Response(
            $this->template->render(
                'movies/search.html.twig',
                [ 'genders' => $genders ]
            )
        );
    }
}