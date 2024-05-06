<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WcPassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wcPass = [
            [
                'student_id' => '1',
                'teacher' => 'Profe 2',
            ],
            [
                'student_id' => ' 2',
                'teacher' => 'Profe 1',
            ],
            // Puedes agregar más estudiantes aquí según sea necesario
        ];

        // Insertar los datos en la tabla 'Students'
        foreach ($wcPass as $wcPas) {
            DB::table('wc_passes')->insert($wcPas);
        }
    }
}
