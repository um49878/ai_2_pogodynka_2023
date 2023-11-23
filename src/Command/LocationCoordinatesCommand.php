<?php

namespace App\Command;

use App\Repository\LocationRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'location:coordinates',
    description: 'Zwraca współrzędne geograficzne po podaniu miasta i kodu kraju',
)]
class LocationCoordinatesCommand extends Command
{
    private LocationRepository $locationRepository;
    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('city', InputArgument::REQUIRED, 'Nazwa miasta')
            ->addArgument('country', InputArgument::OPTIONAL, 'Kod kraju')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $city = $input->getArgument('city');
        $country = $input->getArgument('country');

        $location = $this->locationRepository->findByCityAndCode($input->getArgument('city'), $input->getArgument('country'));


        $io->success('Dane lokacji');
        $io->writeln(sprintf('Współrzędne dla lokacji %s, %s: ', $location->getCity(), $location->getCountry()));
        $io->writeln(sprintf(  "\t długość geogr: %s, \n\t szerokość geograficzna: %s.",$location->getLongitude(),  $location->getLatitude()));

        return Command::SUCCESS;
    }
}
