<?php

namespace App\Movies\Search\Actions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class SearchForMovieAction
{
    /**
     * @Route("/", {"GET"})
     */
    public function __invoke(): Response
    {
        return new Response('<html><body>Boostrap OK</body></html>');
    }
}