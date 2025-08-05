<?php

namespace Database\Factories;

use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domains\User\Models\User>
 */
class UserFactory extends Factory
{

    protected $model = User::class;
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = '12345678';

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'mobile' => fake()->unique()->regexify('09[0-3][0-9]{8}'),
            'password' => bcrypt(static::$password),
        ];
    }
}
