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
                'name' => 'Estudiante 1',
                'surname1' => 'Apellido1',
                'surname2' => 'Apellido2',
                'dni' => '12345678A',
                'birthDate' => '2000-01-01',
                'curs' => 'Clase A',
                'photo' => null,
                'leave' => false,
                'qr' => null,
            ],
            [
                'name' => 'Estudiante 2',
                'surname1' => 'Apellido3',
                'surname2' => 'Apellido4',
                'dni' => '87654321B',
                'birthDate' => '2001-02-02',
                'curs' => 'Clase B',
                'photo' => null,
                'leave' => true,
                'qr' => null,
            ],
            // Puedes agregar más estudiantes aquí según sea necesario
        ];

        // Insertar los datos en la tabla 'students'
        foreach ($students as $student) {
            DB::table('students')->insert($student);
        }
    }
}
