<?php

namespace Database\Factories\Rest;

use App\Models\Rest\Account;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

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
        // 'nik', 'email', 'name', 'gender', 'phone', 'whatsapp', 'photo', 'password', 'role'
        return [
            'nik' => '136136',
            'email' => 'dio_lantief21@outlook.com',//$this->faker->unique()->safeEmail,
            'name' => 'Dio Lantief Widoyoko',//$this->faker->name,
            'gender' => 'male',//$this->faker->randomElement(['male', 'female']),
            'phone' => '085648535927',
            'whatsapp' => '085648535927',
            'photo' => 'default.jpg',
            'role' => '0'
        ];
    }
}
