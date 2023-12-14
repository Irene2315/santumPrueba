<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            [
                'nombre' => 'Chocolate',
                'descripcion' => 'Alimento estimulante nutritivo',
                'cantidad' => '1'
            ],
            [
                'nombre' => 'Leche',
                'descripcion' => 'Contiene lactosa',
                'cantidad' => '10'
            ],
            [
                'nombre' => 'Manzana',
                'descripcion' => 'Fruta tipo acida',
                'cantidad' => '15'
            ]
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
