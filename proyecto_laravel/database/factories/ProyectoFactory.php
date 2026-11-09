<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProyectoFactory extends Factory
{
    public function definition(): array
    {
        $proyecto = fake()->randomElement([
            ['Web de la academia de idiomas', 'Página web con inscripciones online para unos 200 alumnos.'],
            ['Tienda online de ropa', 'Tienda con catálogo, carrito y pagos para una tienda pequeña.'],
            ['App de reservas del restaurante', 'Reservas de mesa desde el móvil y panel para el local.'],
            ['Intranet de la clínica dental', 'Gestión de citas y fichas de pacientes con datos en Europa.'],
            ['Blog del club de fútbol', 'Noticias, resultados y galería de fotos del club.'],
        ]);

        return [
            'nombre' => $proyecto[0],
            'descripcion' => $proyecto[1],
            'presupuesto_maximo' => fake()->randomElement([100, 150, 200, 300, 500]),
        ];
    }
}
