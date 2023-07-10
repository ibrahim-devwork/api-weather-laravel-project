<?php

namespace App\Console\Commands;

use App\Enums\WeatherApiKeyEnum;
use App\Services\GetWeatherService;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class GetWeatherForecastCommand extends Command
{

    protected $getWeatherService;

    public function __construct(GetWeatherService $getWeatherService)
    {
        parent::__construct();
        $this->getWeatherService = $getWeatherService;
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

        $location   = $this->argument('location');
        $days       = min($this->option('days'), 5);
        $units      = $this->option('units');

        $data = $this->getWeatherService->GetWeather($location, $units);

        $this->info("{$data['city']['name']} ({$data['city']['country']})");

        foreach ($data['list'] as $index => $forecast) {
            if ($index >= $days) {
                break;
            }

            $date = date('M d, Y', $forecast['dt']);
            $weather = $forecast['weather'][0]['description'];
            $temperature = $forecast['main']['temp'];

            $this->line($date);
            $this->line("> Weather: $weather");
            $this->line("> Temperature: $temperature °{$units}");
        }
    }
}
