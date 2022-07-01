<?php

namespace App\TMDB\Commands;

use App\TMDB\ClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenderCacheWarmerCommand extends Command
{
    private ClientInterface $client;

    protected static $defaultName = 'tmdb:warmup:genders';

    public function __construct(ClientInterface $client, string $name = null)
    {
        parent::__construct($name);

        $this->client = $client;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            // first call to warmup cache
            $this->client->listGenders();
        } catch (\Exception $e) {
            $io->error('Something bad happened while warming up genders from TMDB : ' . $e->getMessage());

            return Command::FAILURE;
        }

        $io->success('TMDB gendder cache is warmed up');

        return Command::SUCCESS;
    }
}