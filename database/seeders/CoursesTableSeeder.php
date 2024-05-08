<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = [
            ['curs' => 'DAW1'],
            ['curs' => 'DAW2'],
            ['curs' => 'ASIX1'],
            ['curs' => 'ASIX2'],
            ['curs' => 'ASIXDAW'],
        ];

        // Insertar los datos en la tabla 'courses'
        foreach ($courses as $course) {
            DB::table('courses')->insert([
                'curs' => $course['curs'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
