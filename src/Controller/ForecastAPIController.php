<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use App\Service\ForecastUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ForecastAPIController extends AbstractController
{
    #[Route('/api/v1/forecast', name: 'app_forecast_api')]
    public function index(
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $mode,
        ForecastUtil $util,
        LocationRepository $locationsRepo,
        #[MapQueryParameter('twig')] bool $twig = false,
    ): ?Response
    {

        $location = $locationsRepo->findByCityAndCode($city, $country);
        $forecasts = $util->getForecastsForLocation($location);

        if ($twig){
            if ($mode == 'csv') {
                return $this->render('weather_api/index.csv.twig', [
                    'location' => $location,
                    'forecasts' => $forecasts,
                ]);
            }
            if ($mode == 'json'){
                return $this->render('weather_api/index.json.twig',[
                    'location' => $location,
                    'forecasts' => $forecasts,
                ]);
            }
        }

        else{
            if ($mode === 'csv') {
                    $csvLoad = "city,country,date,temperature,wind_direction,wind_speed\n";
                    foreach ($forecasts as $forecast) {
                        $formattedForecasts = [
                            $city,
                            $country,
                            $forecast->getDate()->format('Y-m-d'),
                            $forecast->getTemperature(),
                            $forecast->getFahrenheit(),
                            $forecast->getWindDirection(),
                            $forecast->getWindSpeed()
                        ];

                        $csvLoad .= implode(',', $formattedForecasts) . "\n";
                    return new Response($csvLoad, Response::HTTP_OK, [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="forecast.csv"',
                    ]);
                }
            }
            if ($mode === 'json') {
                $formattedForecasts = [];
                foreach ($forecasts as $forecast) {
                    $formattedForecasts[] = [
                        'date' => $forecast->getDate()->format('Y-m-d'),
                        'temperature' => $forecast->getTemperature(),
                        'fahrenheit' => $forecast->getFahrenheit(),
                        'wind_direction' => $forecast->getWindDirection(),
                        'wind_speed' => $forecast->getWindSpeed(),
                    ];
                }
                $jsonLoad = json_encode([
                    'city' => $city,
                    'country' => $country,
                    'forecasts' => $formattedForecasts,
                ], JSON_PRETTY_PRINT);
                return new Response($jsonLoad, Response::HTTP_OK, [
                    'Contetnt-Type' => 'text/plain',
                    'Content-Disposition' => 'attachment; filename="forecast.json"',
                ]);
            }
        }

    return null;

    }

}
