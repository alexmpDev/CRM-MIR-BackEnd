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
        // Asegúrate de que estos course_id correspondan a cursos realmente existentes en la tabla `courses`
        $students = [
            [
                'name' => 'Estudiante 1',
                'surname1' => 'Apellido1',
                'surname2' => 'Apellido2',
                'dni' => '12345678A',
                'birthDate' => '2000-01-01',
                'course_id' => 1, // Asume que existe un curso con ID 1
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
                'course_id' => 2, // Asume que existe un curso con ID 2
                'photo' => null,
                'leave' => true,
                'qr' => null,
            ],
            // Puedes agregar más estudiantes aquí según sea necesario
        ];

        // Insertar los datos en la tabla 'students'
        foreach ($students as $student) {
            DB::table('students')->insert([
                'name' => $student['name'],
                'surname1' => $student['surname1'],
                'surname2' => $student['surname2'],
                'dni' => $student['dni'],
                'birthDate' => $student['birthDate'],
                'course_id' => $student['course_id'],
                'photo' => $student['photo'],
                'leave' => $student['leave'],
                'qr' => $student['qr'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

       
    
}
