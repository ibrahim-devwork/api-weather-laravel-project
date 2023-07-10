<?php

namespace App\Services;

use App\Enums\WeatherApiKeyEnum;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetForecastWeatherDataService {


    public function getForecastWeatherData($validated_data) {
        
        $forecastData  = [];
        $weatherResult = $this->getForecastWeather($validated_data['location'], $validated_data['units'], $validated_data['days']);
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

        return $forecastData;
    }

    public function getForecastWeather($location, $units, $days) 
    {
        $response = Http::get("http://api.openweathermap.org/data/2.5/forecast", [
            'q'     => $location,
            'units' => $units,
            'cnt'   => $days , 
            'appid' => WeatherApiKeyEnum::Weather_Api_Key->value,
        ]);

        return json_decode($response->getBody(), true);
    }

}


?>