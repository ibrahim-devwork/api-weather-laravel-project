## After cloning the project, you need to run this command to install the project's dependencies :
     composer install
     
## And you need to change key of Openweathermap to your key in this file :
    App\Enums\WeatherApiKeyEnum.php

##  This command to run current weather command :
    php artisan current Havana,CU --units=imperial

##  This command to run forecast weather command :
    php artisan forecast Madrid,ES --days=5 --units=imperial

##  This command to run forecast ask weather command :
    php artisan forecast:ask Madrid,ES --days=5 --units=imperial
