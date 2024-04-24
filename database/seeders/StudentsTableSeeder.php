<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Definir los datos de prueba
        $students = [
            [
                'Name' => 'Estudiante 1',
                'Class' => 'Clase A',
                'Photo' => NULL,
            ],
            [
                'Name' => 'Estudiante 2',
                'Class' => 'Clase B',
                'Photo' => NULL,
            ],
            // Puedes agregar más estudiantes aquí según sea necesario
        ];

        // Insertar los datos en la tabla 'Students'
        foreach ($students as $student) {
            DB::table('students')->insert($student);
        }
    }
}