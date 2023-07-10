<?php

namespace App\Services;

use App\Enums\WeatherApiKeyEnum;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GetWeatherService {

    public function GetWeather($location, $units) 
    {
        $client = new Client();
        $response = $client->get("http://api.openweathermap.org/data/2.5/weather?q={$location}&units={$units}&appid=" . WeatherApiKeyEnum::Weather_Api_Key->value);

        $data = json_decode($response->getBody(), true);

        return $data;
    }
}
?>
