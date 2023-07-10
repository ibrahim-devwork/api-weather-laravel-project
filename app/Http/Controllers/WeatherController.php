<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCurrentWeatherRequest;
use App\Http\Requests\GetWeatherForecastRequest;
use App\Services\GetForecastWeatherDataService;
use App\Services\GetWeatherService;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    protected $getWeatherService;
    protected $getForecastWeatherDataService;

    public function __construct(
        GetWeatherService $getWeatherService,
        GetForecastWeatherDataService $getForecastWeatherDataService
    ) {

        $this->getWeatherService             = $getWeatherService;
        $this->getForecastWeatherDataService = $getForecastWeatherDataService;
    }

    public function getCurrentWeather(GetCurrentWeatherRequest $getCurrentWeatherRequest) 
    {
        try {

            $validated_data  = $getCurrentWeatherRequest->validated();

            $weatherResult = $this->getWeatherService->GetWeather($validated_data['location'], $validated_data['units']);

            return [
                'location'      => "{$weatherResult['name']} ({$weatherResult['sys']['country']})",
                'date'          => date('M d, Y'),
                'weather'       => $weatherResult['weather'][0]['description'],
                'temperature'   => "{$weatherResult['main']['temp']} °{$validated_data['units']}",
            ];

        } catch(\Exception $error) {
            Log::error("WeatherController - (getCurrentWeather) Error : " . $error->getMessage());
            return response()->json(['message' => 'Server error !'], 500);
        }
    }

    public function getWeatherForecast(GetWeatherForecastRequest $getWeatherForecastRequest) {
        try {

            $validated_data       = $getWeatherForecastRequest->validated();
            $result               = $this->getForecastWeatherDataService->getForecastWeatherData($validated_data);
            $weatherResult[]      = $result['weatherResult'];
            $forecastDataResult[] = $result['forecastData'];
        
            return [
                'location' => "{$weatherResult['city']['name']} ({$weatherResult['city']['country']})",
                'forecast' => $forecastDataResult,
            ];

        } catch(\Exception $error) {
            Log::error("WeatherController - (getWeatherForecast) Error : " . $error->getMessage());
            return response()->json(['message' => 'Server error !'], 500);
        }
    }

}
