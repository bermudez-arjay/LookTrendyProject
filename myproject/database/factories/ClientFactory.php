<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'Client_Identity' => $this->faker->unique()->numerify('##############'), 
            'Client_FirstName' => $this->faker->firstName(),
            'Client_LastName' => $this->faker->lastName(),
            'Client_Address' => $this->faker->address(),
            'Client_Phone' => $this->faker->phoneNumber(),
            'Client_Email' => $this->faker->unique()->safeEmail(),
            'Removed' => $this->faker->boolean(5) 
        ];
    }
}