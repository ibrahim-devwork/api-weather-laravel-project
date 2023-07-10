<?php

namespace App\Services;

class GetForecastWeatherDataService {

    protected $getWeatherService;

    public function __construct(GetWeatherService $getWeatherService)
    {
        $this->getWeatherService = $getWeatherService;
    }

    public function getForecastWeatherData($validated_data) {
        
        $forecastData  = [];
        $weatherResult = $this->getWeatherService->GetWeather($validated_data['location'], $validated_data['units']);

        $units         = ($validated_data['units'] === 'metric' ? 'C' : 'F');

        foreach ($weatherResult['list'] as $key => $forecast) {
            if ($key >= $validated_data['days']) {
                break;
            }
    
            $date        = date('M d, Y', $forecast['dt']);
            $weather     = $forecast['weather'][0]['description'];
            $temperature = $forecast['main']['temp'];
    
            $forecastData[] = [
                'date' => $date,
                'weather' => $weather,
                'temperature' => "{$temperature} °{$units}",
            ];
        }

        return [
            "forecastData"  => $forecastData,
            "weatherResult" => $weatherResult
        ];
    }

}


?>