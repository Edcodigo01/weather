<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Ejecuta colas de laravel para consultar datos del clima de los usuarios
Schedule::command('app:check-weather-users')
    ->everyThirtyMinutes();
// ->everyTwentyMinutes();
// ->everyThirtyMinutes();
