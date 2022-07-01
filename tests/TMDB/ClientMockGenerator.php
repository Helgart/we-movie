<?php

namespace App\Tests\TMDB;

use App\TMDB\ClientInterface;
use Mockery;

final class ClientMockGenerator
{
    private const SERVICE_URL = 'http://mock-service.com/';
    private const TOKEN = '1234';

    public static function getMock()
    {
        return Mockery::mock(ClientInterface::class);
    }
}