<?php

namespace App\Services;
class WeatherService
{
    public function __construct(private readonly HttpService $httpService,
                                private readonly int         $days,
                                private readonly string      $temperatureUnit)
    {
    }

    public function getWeather(float $lat, float $lng)
    {

        return $this->httpService->request('GET',
            'https://api.open-meteo.com/v1/forecast',
            ['query' => [
                'latitude' => $lat,
                'longitude' => $lng,
                'daily' => 'temperature_2m_max,temperature_2m_min',
                'timezone' => 'Europe/Paris',
                'forecast_days' => $this->days,
                'temperature_unit' => $this->temperatureUnit,
            ]]
        )->toArray();
    }
}