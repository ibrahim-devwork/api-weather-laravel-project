<?php

namespace App\Console\Commands;


use App\Services\GetCurrentWeatherService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetCurrentWeatherCommand extends Command
{
    protected $getCurrentWeatherService;

    public function __construct(GetCurrentWeatherService $getCurrentWeatherService)
    {
        parent::__construct();
        $this->getCurrentWeatherService = $getCurrentWeatherService;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'current {location=Santander,ES} {--units=metric}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the current weather data for the given location';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {

            $location    = $this->argument('location');
            $units       =  $this->option('units');

            $weatherData = $this->getCurrentWeatherService->GetWeather($location, $units);

            Log::info($weatherData);

            $this->info("{$weatherData['name']} ({$weatherData['sys']['country']})");
            $this->line(date('M d, Y', $weatherData['dt']));
            $this->line("> Weather: {$weatherData['weather'][0]['description']}");
            $this->line('> Temperature: '.$weatherData['main']['temp'].' °'.($units === 'metric' ? 'C' : 'F'));

        } catch(\Exception $error) {
            Log::error("GetCurrentWeatherCommand - (handle) : " . $error->getMessage());
        }
        
    }
}