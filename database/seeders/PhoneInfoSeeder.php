<?php

namespace Database\Seeders;

use App\Models\PhoneInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhoneInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $phones = [
            [
                'student_id' => 1,
                'name' => 'John Doe',
                'phone' => '1234567890',
            ],
            [
                'student_id' => 2,
                'name' => 'Jane Smith',
                'phone' => '9876543210',
            ],
            // Puedes agregar más estudiantes aquí según sea necesario
        ];

        // Insertar los datos en la tabla 'Students'
        foreach ($phones as $phone) {
            DB::table('phone_infos')->insert($phone);
        }
    }
}
