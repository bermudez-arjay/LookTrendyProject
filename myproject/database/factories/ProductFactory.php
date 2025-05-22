<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'Product_Name' => $this->faker->words(3, true),
            'Description' => $this->faker->sentence(),
            'Category' => $this->faker->randomElement(['Calzado',   'Bolsos',   'Higiene',  'Ropa de Dama', 'Ropa de Caballero',  'Ropa Infantil',   'Artículos para el Hogar']),
            'Unit_Price' => $this->faker->randomFloat(2, 10, 500),
            'Removed' => $this->faker->boolean(10) 
        ];
    }
}