<?php

namespace App\Console\Commands;

use App\Services\GetWeatherService;
use Illuminate\Console\Command;

class GetAskForecastWeatherCommand extends Command
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
    protected $signature = 'forecast:ask {location=Santander,ES}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the weather forecast with these questions [ How many days to forecast ? - what unit of measure ? ]';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $location   = $this->argument('location');
        $days       = (int) min($this->ask('How many days to forecast?', 1), 5);
        $units      = $this->choice('What unit of measure?', ['metric', 'imperial'], 0);

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
