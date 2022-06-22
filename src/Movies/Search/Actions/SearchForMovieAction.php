<?php

namespace App\Movies\Search\Actions;

use App\Kernel\Actions\SingleActionControllerInterface;
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
    public function __invoke(): Response
    {
        return new Response(
            $this->template->render('movies/search.html.twig')
        );
    }
}