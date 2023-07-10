<?php

namespace App\Console\Commands;

use App\Services\GetForecastWeatherDataService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetAskForecastWeatherCommand extends Command
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
        try {

            $location   = $this->argument('location');
            $days       = (int) min($this->ask('How many days to forecast?', 1), 5);
            $units      = $this->choice('What unit of measure?', ['metric', 'imperial'], 0);

            $forecastData = $this->getForecastWeatherDataService->getForecastWeather($location, $units, $days);
            $this->info($forecastData['city']['name'].' ('.$forecastData['city']['country'].')');
            
            foreach ($forecastData['list'] as $key => $forecast) {
                if ($key >= $days) {
                    break;
                }
                Log::info($forecast['dt']); 

                $date           = date('M d, Y', $forecast['dt']);
                $weather        = $forecast['weather'][0]['description'];
                $temperature    = $forecast['main']['temp'];

                $this->line($date);
                $this->line("> Weather: $weather");
                $this->line('> Temperature: ' . $temperature .' °' . ($units === 'metric' ? 'C' : 'F'));
                $date=null;
            }

        } catch(\Exception $error) {
            Log::error("GetWeatherForecastCommand - (handle) : " . $error->getMessage());
        }

    }
}
