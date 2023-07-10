<?php

namespace App\Console\Commands;


use App\Services\GetWeatherService;
use Illuminate\Console\Command;

class GetCurrentWeatherCommand extends Command
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
        $location   = $this->argument('location');
        $units      =  $this->option('units');

        $data = $this->getWeatherService->GetWeather($location, $units);

        $this->info("{$data['name']} ({$data['sys']['country']})");
        $this->line(date('M d, Y'));
        $this->line("> Weather: {$data['weather'][0]['description']}");
        $this->line("> Temperature: {$data['main']['temp']} °{$units}");
    }
}