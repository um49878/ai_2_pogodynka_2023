<?php

namespace App\Service;

use App\Entity\Location;
use App\Entity\Forecast;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ForecastUtil
{
    private EntityManagerInterface $entityManager;
    private LocationRepository $locationRepository;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * Pobiera wpisy o pogodzie dla danej lokalizacji.
     *
     * @param Location $location
     * @return Forecast[]|null
     */
    public function getForecastsForLocation(Location $location): ?array
    {
        try {
            $forecasts = $this->entityManager
                ->getRepository(Forecast::class)
                ->findBy(['location' => $location]);

            return $forecasts;
        } catch (\Exception $exception) {
            $this->logger->error('Błąd podczas pobierania prognoz pogody: ' . $exception->getMessage());
            return null;
        }
    }

    /**
     * Pobiera wpisy o pogodzie dla podanego miasta i kraju.
     *
     * @param string $countryCode
     * @param string $city
     * @return Forecast[]|null
     */
    public function getForecastForCountryAndCity(string $countryCode, string $city): ?array
    {
        try {
            $location = $this->locationRepository->findByCityAndCode($city, $countryCode);

            if (!$location) {
                return null;
            }

            $forecasts = $this->entityManager
                ->getRepository(Forecast::class)
                ->findBy(['location' => $location]);

            return $forecasts;
        } catch (\Exception $exception) {
            $this->logger->error('Błąd podczas pobierania prognoz pogody: ' . $exception->getMessage());
            return null;
        }
    }
}
