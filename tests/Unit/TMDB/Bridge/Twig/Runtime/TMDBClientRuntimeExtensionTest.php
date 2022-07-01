<?php

namespace App\Tests\Unit\TMDB\Bridge\Twig\Runtime;

use App\Tests\TMDB\ClientMockGenerator;
use App\Tests\TMDB\Model\MovieGenerator;
use App\Tests\TMDB\Model\VideoGenerator;
use App\Tests\Video\URLGeneratorMockGenerator;
use App\TMDB\Bridge\Twig\Runtime\TMDBClientRuntimeExtension;
use App\Videos\Exception\NotSupportedException;
use PHPUnit\Framework\TestCase;
use Twig\Extension\RuntimeExtensionInterface;

class TMDBClientRuntimeExtensionTest extends TestCase
{

    public function testDIForExtension()
    {
        // Given
        $TMDBClientRuntimeExtension = new TMDBClientRuntimeExtension(
            ClientMockGenerator::getMock(),
            URLGeneratorMockGenerator::getMock()
        );

        // Then
        self::assertInstanceOf(RuntimeExtensionInterface::class, $TMDBClientRuntimeExtension);
    }

    public function testListGenders()
    {
        // Given
        $clientMock = ClientMockGenerator::getMock();
        $TMDBClientRuntimeExtension = new TMDBClientRuntimeExtension(
            $clientMock,
            URLGeneratorMockGenerator::getMock()
        );

        $response = [ new \stdClass(), new \stdClass() ];
        $clientMock
            ->shouldReceive('listGenders')
            ->once()
            ->andReturn($response)
        ;

        // When
        $genders = $TMDBClientRuntimeExtension->listGenders();

        // Then
        self::assertSame($response, $genders);
    }

    public function testFindVideoForMovieWhenSuccessful()
    {
        // Given
        $clientMock = ClientMockGenerator::getMock();
        $URLGeneratorMock = URLGeneratorMockGenerator::getMock();
        $movieFixture = MovieGenerator::getMovieFixtures()[0];
        $videoFixture = VideoGenerator::getVideoFixtures()[0];
        $TMDBClientRuntimeExtension = new TMDBClientRuntimeExtension(
            $clientMock,
            $URLGeneratorMock
        );

        $clientMock
            ->shouldReceive('findVideoForMovie')
            ->with($movieFixture->id)
            ->once()
            ->andReturn($videoFixture)
        ;

        $movieUrl = 'http://youtube.com/url';
        $URLGeneratorMock
            ->shouldReceive('generate')
            ->with($videoFixture)
            ->once()
            ->andReturn($movieUrl)
        ;

        // When
        $generatedUrl = $TMDBClientRuntimeExtension->findVideoForMovie($movieFixture);

        // Then
        self::assertSame($movieUrl, $generatedUrl);
    }

    public function testFindVideoForMovieWhenNotSupported()
    {
        // Given
        $clientMock = ClientMockGenerator::getMock();
        $URLGeneratorMock = URLGeneratorMockGenerator::getMock();
        $movieFixture = MovieGenerator::getMovieFixtures()[0];
        $videoFixture = VideoGenerator::getVideoFixtures()[0];
        $TMDBClientRuntimeExtension = new TMDBClientRuntimeExtension(
            $clientMock,
            $URLGeneratorMock
        );

        $clientMock
            ->shouldReceive('findVideoForMovie')
            ->with($movieFixture->id)
            ->once()
            ->andReturn($videoFixture)
        ;

        $URLGeneratorMock
            ->shouldReceive('generate')
            ->with($videoFixture)
            ->once()
            ->andThrow(NotSupportedException::class)
        ;

        // When
        $generatedUrl = $TMDBClientRuntimeExtension->findVideoForMovie($movieFixture);

        // Then
        self::assertEmpty($generatedUrl);
    }

    public function testFindVideoForMovieWhenFailure()
    {
        // Given
        $clientMock = ClientMockGenerator::getMock();
        $URLGeneratorMock = URLGeneratorMockGenerator::getMock();
        $movieFixture = MovieGenerator::getMovieFixtures()[0];
        $videoFixture = VideoGenerator::getVideoFixtures()[0];
        $TMDBClientRuntimeExtension = new TMDBClientRuntimeExtension(
            $clientMock,
            $URLGeneratorMock
        );

        $clientMock
            ->shouldReceive('findVideoForMovie')
            ->with($movieFixture->id)
            ->once()
            ->andReturn($videoFixture)
        ;

        $URLGeneratorMock
            ->shouldReceive('generate')
            ->with($videoFixture)
            ->once()
            ->andThrow(\Exception::class)
        ;

        $this->expectException(\Exception::class);

        // When
        $TMDBClientRuntimeExtension->findVideoForMovie($movieFixture);
    }
}
