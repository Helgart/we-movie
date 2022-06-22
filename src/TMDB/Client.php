<?php

namespace App\TMDB;

use App\TMDB\Model\Gender;
use App\TMDB\Model\Movie;
use App\TMDB\Model\MovieCollection;
use App\TMDB\Model\Video;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Client
{
    const TMDB_API_GENDERS_CACHE_TTL = '30 days';
    const TMDB_API_GENDERS_CACHE_KEY = 'tmdb_genders';

    const TMDB_API_BEST_MOVIE_CACHE_TTL = '30 days';
    const TMDB_API_BEST_MOVIE_CACHE_KEY = 'tmdb_best_movie';

    const TMDB_API_VIDEO_CACHE_TTL = '30 days';
    const TMDB_API_VIDEO_CACHE_KEY = 'tmdb_video_%s';

    const TMDB_API_SUPPORTED_VERSION = 3;
    const TMDB_API_SUPPORTED_LANGUAGE = 'fr-FR';
    const DEFAULT_QUERY_PARAMETERS = [ 'language' => self::TMDB_API_SUPPORTED_LANGUAGE ];

    const TMDB_API_GENDERS_LIST_ENDPOINT = 'genre/movie/list';
    const TMDB_API_TOP_RATED_LIST_ENDPOINT = 'movie/top_rated';
    const TMDB_API_GET_VIDEO_ENDPOINT = 'movie/%s/videos';
    const TMDB_API_SEARCH_MOVIE_ENDPOINT = 'discover/movie';

    private string $serviceURL;
    private string $token;

    private HttpClientInterface $httpClient;
    private SerializerInterface $serializer;
    private CacheInterface $cache;

    public function __construct(
        string $serviceURL,
        string $token,
        CacheInterface $cache
    ) {
        $this->serviceURL = $serviceURL;
        $this->token = $token;
        $this->cache = $cache;

        $this->serializer = new Serializer([
            new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter())
        ], [
            'json' => new JsonEncoder()
        ]);

        $this->httpClient = HttpClient::createForBaseUri(
            sprintf('%s/%s/', $this->serviceURL, self::TMDB_API_SUPPORTED_VERSION),
            [
                'headers' => [
                    'Content-Type' => 'application/json;charset=utf-8',
                    'Authorization' => sprintf('Bearer %s', $this->token)
                ],
                'query' => self::DEFAULT_QUERY_PARAMETERS
            ]
        );
    }

    /**
     * @return Gender[]
     */
    public function listGenders(): array
    {
        return $this
            ->cache
            ->get(
                self::TMDB_API_GENDERS_CACHE_KEY,
                function (ItemInterface $item) {
                    $item->expiresAfter(\DateInterval::createFromDateString(self::TMDB_API_GENDERS_CACHE_TTL));

                    $response = $this
                        ->httpClient
                        ->request(Request::METHOD_GET, self::TMDB_API_GENDERS_LIST_ENDPOINT)
                    ;

                    return array_map(
                        fn ($rawGender) => $this->serializer->denormalize($rawGender, Gender::class),
                        $response->toArray()['genres'] ?? []
                    );
                }
            )
        ;
    }

    public function findMovieByGender(int $genderId, int $page = 1): MovieCollection
    {
        $response = $this
            ->httpClient
            ->request(
                Request::METHOD_GET,
                self::TMDB_API_SEARCH_MOVIE_ENDPOINT,
                [
                    'query' => array_merge(
                        self::DEFAULT_QUERY_PARAMETERS,
                        [
                            'with_genres' => $genderId,
                            'page' => $page
                        ]
                    )
                ]
            )
        ;

        $rawMoviesCollection = $response->toArray();

        return new MovieCollection(
            $rawMoviesCollection['page'] ?? 0,
            $rawMoviesCollection['total_pages'] ?? 0,
            $rawMoviesCollection['total_results'] ?? 0,
            array_map(
                fn ($rawMovie) => $this->serializer->denormalize($rawMovie, Movie::class),
                $response->toArray()['results'] ?? []
            )
        );
    }

    public function findVideoForMovie(int $movieId): Video
    {
        return $this
            ->cache
            ->get(
                sprintf(self::TMDB_API_VIDEO_CACHE_KEY, $movieId),
                function (ItemInterface $item) use ($movieId) {
                    $item->expiresAfter(\DateInterval::createFromDateString(self::TMDB_API_VIDEO_CACHE_TTL));

                    $videoResponse = $this
                        ->httpClient
                        ->request(Request::METHOD_GET, sprintf(self::TMDB_API_GET_VIDEO_ENDPOINT, $movieId))
                    ;

                    return $this->serializer->denormalize(
                        $videoResponse->toArray()['results'][0] ?? [],
                        Video::class
                    );
                }
            )
            ;
    }

    public function findBestMovie(): Movie
    {
        return $this
            ->cache
            ->get(
                self::TMDB_API_BEST_MOVIE_CACHE_KEY,
                function (ItemInterface $item) {
                    $item->expiresAfter(\DateInterval::createFromDateString(self::TMDB_API_BEST_MOVIE_CACHE_TTL));

                    $bestMovieResponse = $this
                        ->httpClient
                        ->request(Request::METHOD_GET, self::TMDB_API_TOP_RATED_LIST_ENDPOINT)
                    ;

                    return $this->serializer->denormalize(
                        $bestMovieResponse->toArray()['results'][0] ?? [],
                        Movie::class
                    );
                }
            )
        ;
    }
}