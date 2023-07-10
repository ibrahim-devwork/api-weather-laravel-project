<?php

namespace App\Services;

use App\Enums\WeatherApiKeyEnum;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetCurrentWeatherService {

    public function GetWeather($location, $units) 
    {

        $params = [
            'q'     => $location,
            'units' => $units,
            'appid' => WeatherApiKeyEnum::Weather_Api_Key->value,
        ];

        $response = Http::get("http://api.openweathermap.org/data/2.5/weather", $params);
        return json_decode($response->getBody(), true);
    }
}
?>
