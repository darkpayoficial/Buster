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
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'password' => static::$password ??= Hash::make('password'),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'telefone' => fake()->phoneNumber(),
            'cnpj' => fake()->numerify('##.###.###/####-##'),
            'faturamento' => fake()->randomElement(['PEQUENO', 'MEDIO', 'GRANDE']),
            'documents_checked' => fake()->boolean() ? 1 : 0,
            'pin' => fake()->numerify('####'),
            'cash_in_active' => fake()->boolean() ? 1 : 0,
            'cash_out_active' => fake()->boolean() ? 1 : 0,
            'balance' => fake()->randomFloat(6, 0, 10000),
            'total_deposit' => fake()->randomFloat(2, 0, 50000),
            'total_withdraw' => fake()->randomFloat(2, 0, 30000),
            'total_tax_paid' => fake()->randomFloat(2, 0, 5000),
            'role' => fake()->randomElement(['USER', 'ADMIN']),
            'app_token' => fake()->optional()->sha256(),
            'twofa_secret' => fake()->optional()->sha256(),
            'twofa_enabled' => fake()->boolean(),
            'google2fa_secret' => fake()->optional()->sha256(),
            'google2fa_enabled' => fake()->boolean(),
            'exibir_token_login' => fake()->boolean(),
            'last_login' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
            'last_ip' => fake()->optional()->ipv4(),
            'last_logout' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Indicate that the user should be an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'ADMIN',
        ]);
    }

    /**
     * Indicate that the user should be a regular user.
     */
    public function user(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'USER',
        ]);
    }

    /**
     * Indicate that the user has verified documents.
     */
    public function verifiedDocuments(): static
    {
        return $this->state(fn (array $attributes) => [
            'documents_checked' => 1,
        ]);
    }

    /**
     * Indicate that two-factor authentication is enabled.
     */
    public function withTwoFactor(): static
    {
        return $this->state(fn (array $attributes) => [
            'twofa_enabled' => true,
            'twofa_secret' => Str::random(32),
        ]);
    }

    /**
     * Indicate that Google 2FA is enabled.
     */
    public function withGoogle2FA(): static
    {
        return $this->state(fn (array $attributes) => [
            'google2fa_enabled' => true,
            'google2fa_secret' => Str::random(32),
        ]);
    }
}
