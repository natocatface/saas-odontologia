<?php

namespace Database\Seeders;

use App\Models\Tratamiento;
use Illuminate\Database\Seeder;

class TratamientoSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['Consulta y diagnostico', 'Diagnostico', 150, 30],
            ['Radiografia periapical', 'Diagnostico', 80, 15],
            ['Limpieza dental (profilaxis)', 'Preventiva', 200, 40],
            ['Aplicacion de fluor', 'Preventiva', 120, 20],
            ['Sellado de fosas y fisuras', 'Preventiva', 100, 25],
            ['Resina simple', 'Restauracion', 250, 45],
            ['Resina compuesta', 'Restauracion', 350, 60],
            ['Incrustacion', 'Restauracion', 600, 60],
            ['Tratamiento de conducto (1 conducto)', 'Endodoncia', 700, 60],
            ['Tratamiento de conducto (multiradicular)', 'Endodoncia', 1200, 90],
            ['Extraccion simple', 'Cirugia', 300, 30],
            ['Extraccion de cordal', 'Cirugia', 800, 60],
            ['Brackets metalicos (instalacion)', 'Ortodoncia', 2500, 90],
            ['Control de ortodoncia', 'Ortodoncia', 200, 30],
            ['Curetaje por cuadrante', 'Periodoncia', 400, 45],
            ['Blanqueamiento dental', 'Estetica', 1500, 75],
            ['Carilla de porcelana', 'Estetica', 1800, 90],
            ['Corona de porcelana', 'Protesis', 1600, 90],
            ['Protesis parcial removible', 'Protesis', 2200, 60],
            ['Aplicacion de sellantes (nino)', 'Odontopediatria', 120, 30],
        ];

        foreach ($items as [$nombre, $categoria, $precio, $duracion]) {
            Tratamiento::updateOrCreate(
                ['nombre' => $nombre],
                ['categoria' => $categoria, 'precio' => $precio, 'duracion_min' => $duracion, 'activo' => true]
            );
        }
    }
}
