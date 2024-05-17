<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WcPassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wcPasses = [
            [
                'student_id' => 1, // Asegúrate de que estos IDs correspondan a estudiantes reales en tu base de datos
                'teacher' => 'Profe 2',
                'valid_until' => Carbon::now()->addHours(1) // Añade un tiempo de validez de 1 hora desde la creación
            ],
            [
                'student_id' => 2, // Eliminar el espacio en blanco después del número
                'teacher' => 'Profe 1',
                'valid_until' => Carbon::now()->addHours(1)
            ],
            // Puedes agregar más entradas aquí según sea necesario
        ];

        // Insertar los datos en la tabla 'wc_passes'
        foreach ($wcPasses as $pass) {
            DB::table('wc_passes')->insert([
                'student_id' => $pass['student_id'],
                'teacher' => $pass['teacher'],
                'valid_until' => $pass['valid_until']->format('Y-m-d H:i:s'), // Formatear la fecha para SQL
                'created_at' => now(),  // Añadir marcas de tiempo
                'updated_at' => now()
            ]);
        }
    }
}
