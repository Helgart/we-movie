<?php

namespace App\Tests\Command;

use Mockery;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class OutputMockGenerator
{
    public static function getMockUsedBySymfonyStyle()
    {
        $outputMock = Mockery::mock(OutputInterface::class);
        $outputFormaterMock = Mockery::mock(OutputFormatterInterface::class);

        $outputFormaterMock
            ->shouldReceive('setDecorated')
            ->zeroOrMoreTimes()
            ->with(Mockery::any())
        ;
        $outputFormaterMock
            ->shouldReceive('isDecorated')
            ->zeroOrMoreTimes()
            ->andReturn(false)
        ;
        $outputFormaterMock->shouldIgnoreMissing();

        $outputMock
            ->shouldReceive('getFormatter')
            ->zeroOrMoreTimes()
            ->andReturn($outputFormaterMock)
        ;
        $outputMock->shouldIgnoreMissing();

        return $outputMock;
    }
}