<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reservations = [
            [
                'book_id' => '1',
                'student_id' => '2',
                'return_date' => new DateTime('tomorrow'),
                'returned' => false,
            ],
            [
                'book_id' => '2',
                'student_id' => '1',
                'return_date' => new DateTime('tomorrow'),
                'returned' => true,
            ],
            // Puedes agregar más estudiantes aquí según sea necesario
        ];

        // Insertar los datos en la tabla 'Students'
        foreach ($reservations as $reservation) {
            DB::table('reservations')->insert($reservation);
        }
    }
}
