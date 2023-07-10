<?php

namespace App\Console\Commands;

use App\Enums\WeatherApiKeyEnum;
use App\Services\GetForecastWeatherDataService;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetWeatherForecastCommand extends Command
{

    protected $getForecastWeatherDataService;

    public function __construct(GetForecastWeatherDataService $getForecastWeatherDataService)
    {
        parent::__construct();
        $this->getForecastWeatherDataService = $getForecastWeatherDataService;
    }
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forecast {location=Santander,ES} {--days=1} {--units=metric}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the weather forecast for max 5 days for the given location.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {

            $location   = $this->argument('location');
            $days       = min($this->option('days'), 5);
            $units      = $this->option('units');

            $forecastData = $this->getForecastWeatherDataService->getForecastWeather($location, $units, $days);
            $this->info($forecastData['city']['name'].' ('.$forecastData['city']['country'].')');
            
            $previousDate = null;
            foreach ($forecastData['list'] as $key => $forecast) {
                $date    = date('M d, Y', $forecast['dt']);
                if ($previousDate !== $date) {
                    $previousDate = $date;
                    $weather        = $forecast['weather'][0]['description'];
                    $temperature    = $forecast['main']['temp'];

                    $this->line($date);
                    $this->line("> Weather: $weather");
                    $this->line('> Temperature: ' . $temperature .' °' . ($units === 'metric' ? 'C' : 'F'));

                    $days--;
                    if ($days == 0) {
                        break;
                    }
                }
            }

        } catch(\Exception $error) {
            Log::error("GetWeatherForecastCommand - (handle) : " . $error->getMessage());
        }
    }
}