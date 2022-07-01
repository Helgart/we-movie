<?php

namespace App\Tests\TMDB\Model;

use App\TMDB\Model\Video;
use Nelmio\Alice\Loader\NativeLoader;

final class VideoGenerator
{
    public static function getVideoFixtures(?int $number = null): array
    {
        $key = $number ? "video_{1..$number}" : 'video';

        return array_values(
            (new NativeLoader())
                ->loadData([
                    Video::class => [
                        $key => [
                            'id' => '<randomNumber()>',
                            'name' => '<word()>',
                            'key' => '<word()>',
                            'site' => '<url()>'
                        ]
                    ]
                ])
                ->getObjects()
        );
    }
}