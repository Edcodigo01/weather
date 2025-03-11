<?php

namespace Tests\Feature;

use App\Jobs\CheckWeatherUsersJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Queue;
use Tests\TestCase;

class CheckWeatherUsersTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        // Finge que las colas están funcionando
        Queue::fake();

        $user = User::first();

        // Dispara el trabajo en cola
        CheckWeatherUsersJob::dispatch($user);

        // Trabajo en cola añadido
        Queue::assertPushed(CheckWeatherUsersJob::class);
    }
}
