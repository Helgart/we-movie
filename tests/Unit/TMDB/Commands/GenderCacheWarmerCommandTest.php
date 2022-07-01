<?php

namespace App\Tests\Unit\TMDB\Commands;

use App\Tests\Command\InputMockGenerator;
use App\Tests\Command\OutputMockGenerator;
use App\Tests\TMDB\ClientMockGenerator;
use App\TMDB\Commands\GenderCacheWarmerCommand;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Component\Console\Command\Command;

class GenderCacheWarmerCommandTest extends MockeryTestCase
{
    public function testExecutionWhenEverythingIsFine()
    {
        // Given
        $clientMock = ClientMockGenerator::getMock();
        $inputMock = InputMockGenerator::getMock();
        $outputMock = OutputMockGenerator::getMockUsedBySymfonyStyle();

        $clientMock
            ->shouldReceive('listGenders')
            ->once()
        ;
        $inputMock->shouldIgnoreMissing();
        $outputMock->shouldIgnoreMissing();

        $genderCacheWarmerCommand = new GenderCacheWarmerCommand($clientMock);

        // When
        $result = $genderCacheWarmerCommand->run($inputMock, $outputMock);

        // Then
        self::assertEquals(Command::SUCCESS, $result);
    }

    public function testExecutionWhenClientIsFailing()
    {
        // Given
        $clientMock = ClientMockGenerator::getMock();
        $inputMock = InputMockGenerator::getMock();
        $outputMock = OutputMockGenerator::getMockUsedBySymfonyStyle();

        $clientMock
            ->shouldReceive('listGenders')
            ->andThrows(\Exception::class)
        ;

        $genderCacheWarmerCommand = new GenderCacheWarmerCommand($clientMock);

        // When
        $result = $genderCacheWarmerCommand->run($inputMock, $outputMock);

        // Then
        self::assertEquals(Command::FAILURE, $result);
    }
}
