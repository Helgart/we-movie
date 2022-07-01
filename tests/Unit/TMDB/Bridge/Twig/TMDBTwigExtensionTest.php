<?php

namespace App\Tests\Unit\TMDB\Bridge\Twig;

use App\Tests\TMDB\Model\MovieGenerator;
use App\TMDB\Bridge\Twig\Runtime\TMDBClientRuntimeExtension;
use App\TMDB\Bridge\Twig\TMDBTwigExtension;
use PHPUnit\Framework\TestCase;
use Twig\Extension\AbstractExtension;

class TMDBTwigExtensionTest extends TestCase
{
    private const POSTER_URL = 'http://poster.url.com/';

    public function testDIForExtension()
    {
        // Given
        $TMDBTwigExtension = new TMDBTwigExtension(self::POSTER_URL);

        // Then
        self::assertInstanceOf(AbstractExtension::class, $TMDBTwigExtension);
    }

    public function testFindImageForMovie()
    {
        // Given
        $movieFixture = MovieGenerator::getMovieFixtures()[0];
        $TMDBTwigExtension = new TMDBTwigExtension(self::POSTER_URL);

        // When
        $url = $TMDBTwigExtension->findImageForMovie($movieFixture);

        // Then
        self::assertEquals(
            self::POSTER_URL . '/t/p/w200' . $movieFixture->posterPath,
            $url
        );
    }

    public function testGetFunctions()
    {
        // Given
        $TMDBTwigExtension = new TMDBTwigExtension(self::POSTER_URL);
        $shouldBeDeclaredOnce = [
            'tmdb_genders' => [
                'targetFQDN' => TMDBClientRuntimeExtension::class,
                'targetMethod' => 'listGenders'
            ],
            'tmdb_video_for_movie' => [
                'targetFQDN' => TMDBClientRuntimeExtension::class,
                'targetMethod' => 'findVideoForMovie'
            ],
            'tmdb_image_for_movie' => [
                'targetFQDN' => TMDBTwigExtension::class,
                'targetMethod' => 'findImageForMovie'
            ]
        ];

        // When
        $functions = $TMDBTwigExtension->getFunctions();

        // Then
        foreach ($functions as $function) {
            $callable = $function->getCallable();
            $name = $function->getName();

            self::assertTrue(array_key_exists($name, $shouldBeDeclaredOnce));
            self::assertEquals(
                is_object($callable[0]) ?
                    get_class($callable[0]) :
                    $callable[0],
                $shouldBeDeclaredOnce[$name]['targetFQDN']);
            self::assertEquals($callable[1], $shouldBeDeclaredOnce[$name]['targetMethod']);
        }
    }
}
