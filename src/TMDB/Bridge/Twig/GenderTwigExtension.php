<?php

namespace App\TMDB\Bridge\Twig;

use App\TMDB\Bridge\Twig\Runtime\TMDBClientRuntimeExtension;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class GenderTwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('tmdb_genders', [ TMDBClientRuntimeExtension::class, 'listGenders' ])
        ];
    }
}