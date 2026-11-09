<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    public function definition(): array
    {
        $producto = fake()->unique()->randomElement([
            ['Servidor virtual pequeño (2 vCPU, 4 GB)', 'computacion', 18.50, 'mensual'],
            ['Servidor virtual mediano (4 vCPU, 8 GB)', 'computacion', 35.90, 'mensual'],
            ['Servidor virtual grande (8 vCPU, 16 GB)', 'computacion', 69.00, 'mensual'],
            ['Almacenamiento SSD 100 GB', 'almacenamiento', 9.80, 'mensual'],
            ['Almacenamiento de objetos 500 GB', 'almacenamiento', 12.40, 'mensual'],
            ['Base de datos MySQL gestionada', 'base_datos', 24.90, 'mensual'],
            ['Base de datos PostgreSQL gestionada', 'base_datos', 27.50, 'mensual'],
            ['Copia de seguridad diaria', 'backup', 6.75, 'mensual'],
            ['Certificado SSL y dominio', 'red', 15.00, 'unico'],
            ['Migración inicial de datos', 'servicio', 49.00, 'unico'],
            ['Configuración del cortafuegos', 'seguridad', 35.00, 'unico'],
            ['Balanceador de carga', 'red', 21.30, 'mensual'],
        ]);

        return [
            'nombre' => $producto[0],
            'tipo' => $producto[1],
            'precio' => $producto[2],
            'modalidad' => $producto[3],
            'activo' => true,
        ];
    }
}
