<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected $latLongCount = 0;

    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $weather = $this->getLatLongValid();
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'latitude' => $weather["lat"],
            'longitude' => $weather["lon"],
        ];
    }

    // Se hace esta función por que al general latitudes y longitudes aleatorios, en algunos casos no coincide con la información de weatherapi.com, si se ingresaran por formualario deberia validarse antes de guardar esa información.
    public function getLatLongValid()
    {
        $coordinates = [
            [
                "lat" => 73.5,
                "lon" => -21.5667
            ],
            [
                "lat" => 5.84,
                "lon" => -5.36
            ],
            [
                "lat" => -27.233,
                "lon" => 27.583
            ],
            [
                "lat" => -11.4167,
                "lon" => 130.4333
            ],
            [
                "lat" => -35.217,
                "lon" => 173.967
            ],
            [
                "lat" => 35.75,
                "lon" => 136.5
            ],
            [
                "lat" => -37.83,
                "lon" => 77.55
            ],
            [
                "lat" => 21.45,
                "lon" => -82.9333
            ],
            [
                "lat" => 21.45,
                "lon" => -82.9333
            ],
            [
                "lat" => 9.7261,
                "lon" => 118.4503
            ],
            [
                "lat" => -0.1395,
                "lon" => 98.186
            ],
            [
                "lat" => 57.1606,
                "lon" => -153.1564
            ],
            [
                "lat" => 37.7667,
                "lon" => 124.75
            ],
            [
                "lat" => 56.8353,
                "lon" => -135.3728
            ],
            [
                "lat" => 40.15,
                "lon" => 73.45
            ],
            [
                "lat" => 35.7353,
                "lon" => -105.0378
            ],
            [
                "lat" => 14.1833,
                "lon" => 25.0833
            ],
            [
                "lat" => -15.2167,
                "lon" => -55.5
            ],
            [
                "lat" => 49.4833,
                "lon" => -126.4
            ],
            [
                "lat" => 36.6569,
                "lon" => 68.3697
            ]
        ];

        $latLong = $coordinates[$this->latLongCount];
        $this->latLongCount++;
        return $latLong;
    }
    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
