<?php

namespace Database\Factories;

use App\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'balance' => $this->faker->numberBetween(-1000, 1000),
            'user_id' => \App\User::factory(),
        ];
    }
}
