<?php

namespace App\Console\Commands;

use App\Jobs\CheckWeatherUsersJob;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class checkWeatherUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-weather-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Conulta clima para todos los usuarios';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // BORRAR------------
        DB::table('schedule_count')->insert([
            'type' => 'schedule',
            'user_id' => 1,
            'created_at' => now()
        ]);

        $users = User::all("id");

        foreach ($users as $user) {
            CheckWeatherUsersJob::dispatch($user);
        }
    }
}
