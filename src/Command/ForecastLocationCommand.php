<?php

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\ForecastUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'forecast:location',
    description: 'Zwraca pogodę dla podanej w wywołaniu lokacji',
)]
class ForecastLocationCommand extends Command
{
    private ForecastUtil $forecastUtil;
    private LocationRepository $locationRepository;
    public function __construct(ForecastUtil $forecastUtil, LocationRepository $locationRepository)
    {
        $this->forecastUtil = $forecastUtil;
        $this->locationRepository = $locationRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'ID lokacji');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $locationId = $input->getArgument('id');
        $location = $this->locationRepository->find($locationId);

        $forecasts = $this->forecastUtil->getForecastsForLocation($location);

        $io->success('Prognoza pogody');
        $io->writeln(sprintf('Lokacja: %s, %s', $location->getcity(), $location->getCountry()));
        foreach($forecasts as $forecast){
            $io-> writeln(sprintf("\t%s: temperatura: %s°C, wiatr: %s, %s m/s",
                $forecast->getDate()->format('Y-m-d'),
                $forecast->getTemperature(),
                $forecast->getWindDirection(),
                $forecast->getWindSpeed()));
        }
        return Command::SUCCESS;
    }
}
