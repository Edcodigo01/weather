<?php

namespace App\Jobs;

use App\Models\User;
use Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Cache;

class CheckWeatherUsersJob implements ShouldQueue
{
    use Queueable;
    protected $user;

    // Número de intentos antes de fallar el trabajo
    public $tries = 3;

    // Intervalo de reintento cada 5 minutos
    public $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $latitude = $this->user->latitude;
        $longitude = $this->user->longitude;
        $apiKey = config('services.weatherapi.key');

        $response = Http::get("http://api.weatherapi.com/v1/current.json?lang=3s", [
            'key' => $apiKey,
            'q' => "{$latitude},{$longitude}",
            'lang' => 'es'
        ]);

        if ($response->successful()) {
            try {

                $weatherApiResponse = $response->json();

                $weatherData = [
                    'user_id' => $this->user->id,
                    'location' => $weatherApiResponse['location']['name'],
                    'region' => $weatherApiResponse['location']['region'],
                    'country' => $weatherApiResponse['location']['country'],
                    'localtime' => $weatherApiResponse['location']['localtime'],
                    'temp_c' => $weatherApiResponse['current']["temp_c"],
                    'is_day' => $weatherApiResponse['current']["is_day"],
                    'condition_text' => $weatherApiResponse['current']['condition']["text"],
                    'condition_icon' => $weatherApiResponse['current']['condition']["icon"],
                    'wind_dir' => $weatherApiResponse['current']["wind_dir"],
                    'wind_kph' => $weatherApiResponse['current']["wind_kph"],
                    'feelslike_c' => $weatherApiResponse['current']["feelslike_c"],
                    'gust_kph' => $weatherApiResponse['current']["gust_kph"],
                ];

                // Almacenar los datos del clima
                $this->user->weather()->updateOrCreate(
                    ['user_id' => $this->user->id],
                    $weatherData
                );

                // Borra datos de cache para que la tabla usuarios muestre los datos nuevos
                Cache::tags(["user-list"])->flush();
                // Borra datos de cache relacionados al usuario, para que el modal muestre los datos actualizados
                Cache::forget("user-list-" . $this->user->id);

            } catch (\Throwable $th) {
                $this->fail($th);
            }
        } else {
            $this->fail();
        }
    }
}
