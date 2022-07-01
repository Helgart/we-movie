<?php

namespace App\Tests\Command;

use Mockery;
use Symfony\Component\Console\Input\InputInterface;

final class InputMockGenerator
{
    public static function getMock()
    {
        $inputMock = Mockery::mock(InputInterface::class);

        $inputMock->shouldIgnoreMissing();

        return $inputMock;
    }
}