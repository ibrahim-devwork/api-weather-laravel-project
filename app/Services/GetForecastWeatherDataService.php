<?php

namespace App\Services;

use App\Enums\WeatherApiKeyEnum;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetForecastWeatherDataService {


    public function getForecastWeatherData($validated_data) {
        
        $forecastData  = [];
        $days           = $validated_data['days'];
        $weatherResult = $this->getForecastWeather($validated_data['location'], $validated_data['units'], $days);
        $units         = ($validated_data['units'] === 'metric' ? 'C' : 'F');

        $previousDate = null;
        foreach ($weatherResult['list'] as $key => $forecast) {
            $date    = date('M d, Y', $forecast['dt']);
            if ($previousDate !== $date) {
                $previousDate = $date;
                $weather        = $forecast['weather'][0]['description'];
                $temperature    = $forecast['main']['temp'];
                $weather     = $forecast['weather'][0]['description'];
                $temperature = $forecast['main']['temp'];
        
                $forecastData[] = [
                    'date' => $date,
                    'weather' => $weather,
                    'temperature' => "{$temperature} °{$units}",
                ];
                
                $days--;
                if ($days == 0) {
                    break;
                }
            }
    
            
        }
 
        return $forecastData;
    }

    public function getForecastWeather($location, $units, $days) 
    {
        $response = Http::get("http://api.openweathermap.org/data/2.5/forecast", [
            'q'     => $location,
            'units' => $units,
            'cnt'   => ($days * 8), 
            'appid' => WeatherApiKeyEnum::Weather_Api_Key->value,
        ]);

        return json_decode($response->getBody(), true);
    }

}


?>