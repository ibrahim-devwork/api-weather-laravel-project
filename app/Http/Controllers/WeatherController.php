<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetCurrentWeatherRequest;
use App\Http\Requests\GetWeatherForecastRequest;
use App\Services\GetCurrentWeatherService;
use App\Services\GetForecastWeatherDataService;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    protected $getCurrentWeatherService;
    protected $getForecastWeatherDataService;

    public function __construct(
        GetCurrentWeatherService $getCurrentWeatherService,
        GetForecastWeatherDataService $getForecastWeatherDataService
    ) {

        $this->getCurrentWeatherService             = $getCurrentWeatherService;
        $this->getForecastWeatherDataService = $getForecastWeatherDataService;
    }

    public function getCurrentWeather(GetCurrentWeatherRequest $getCurrentWeatherRequest) 
    {
        try {

            $validated_data  = $getCurrentWeatherRequest->validated();

            $weatherResult = $this->getCurrentWeatherService->GetWeather($validated_data['location'], $validated_data['units']);

            $units         = ($validated_data['units'] === 'metric' ? 'C' : 'F');
            return [
                'location'      => "{$weatherResult['name']} ({$weatherResult['sys']['country']})",
                'date'          => date('M d, Y'),
                'weather'       => $weatherResult['weather'][0]['description'],
                'temperature'   => "{$weatherResult['main']['temp']} °{$units}",
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
            $forecastDataResult[] = $result;
            
            return [
                'location' => $validated_data['location'],
                'forecast' => $forecastDataResult,
            ];

        } catch(\Exception $error) {
            Log::error("WeatherController - (getWeatherForecast) Error : " . $error->getMessage());
            return response()->json(['message' => 'Server error !'], 500);
        }
    }

}
