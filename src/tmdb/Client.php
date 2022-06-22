<?php

namespace App\tmdb;

use App\tmdb\Model\Gender;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Client
{
    const TMDB_API_SUPPORTED_VERSION = 3;
    const TMDB_API_SUPPORTED_LANGUAGE = 'fr-FR';
    const TMDB_API_GENDERS_LIST_ENDPOINT = 'genre/movie/list';

    private string $serviceURL;
    private string $token;
    private HttpClientInterface $httpClient;
    private SerializerInterface $serializer;

    public function __construct(
        string $serviceURL,
        string $token,
        SerializerInterface $serializer
    ) {
        $this->serviceURL = $serviceURL;
        $this->token = $token;
        $this->serializer = $serializer;

        $this->httpClient = HttpClient::createForBaseUri(
            sprintf('%s/%s/', $this->serviceURL, self::TMDB_API_SUPPORTED_VERSION),
            [
                'headers' => [
                    'Content-Type' => 'application/json;charset=utf-8',
                    'Authorization' => sprintf('Bearer %s', $this->token)
                ],
                'query' => [
                    'language' => self::TMDB_API_SUPPORTED_LANGUAGE
                ]
            ]
        );
    }

    /**
     * @return Gender[]
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function listGenders(): array
    {
        $response = $this
            ->httpClient
            ->request(Request::METHOD_GET, self::TMDB_API_GENDERS_LIST_ENDPOINT)
        ;

        return array_map(
            fn ($rawGender) => $this->serializer->denormalize($rawGender, Gender::class),
            $response->toArray()['genres'] ?? []
        );
    }
}