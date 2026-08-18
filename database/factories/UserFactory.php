<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/** @extends Factory<User> */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'identificador' => strtoupper(fake()->unique()->bothify('????######H??????#')),
            'email_recuperacion' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('Password123!'),
            'rol' => 'Administrador',
            'estatus' => true,
            'primer_ingreso' => false,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => ['estatus' => false]);
    }

    public function firstLogin(): static
    {
        return $this->state(fn (): array => ['primer_ingreso' => true]);
    }

    public function role(string $role): static
    {
        return $this->state(fn (): array => ['rol' => $role]);
    }
}
