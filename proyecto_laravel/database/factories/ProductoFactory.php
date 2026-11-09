<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => 'Servidor ' . fake()->unique()->numberBetween(1, 999),
            'tipo' => fake()->randomElement(['computacion', 'almacenamiento', 'base_datos']),
            'precio' => fake()->randomFloat(2, 5, 100),
            'modalidad' => fake()->randomElement(['mensual', 'unico']),
            'activo' => true,
        ];
    }
}
