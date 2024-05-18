<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('adminpassword'),
                'role_id' => 1
            ],
            [
                'name' => 'Professor',
                'email' => 'professor@example.com',
                'password' => Hash::make('professorpassword'),
                'role_id' => 2
            ],
            [
                'name' => 'Biblioteca',
                'email' => 'biblioteca@example.com',
                'password' => Hash::make('bibliotecapassword'),
                'role_id' => 3
            ],
            [
                'name' => 'Direcció',
                'email' => 'direccio@example.com',
                'password' => Hash::make('direcciopassword'),
                'role_id' => 4
            ]
            // Puedes agregar más estudiantes aquí según sea necesario
        ];

        // Insertar los datos en la tabla 'Students'
        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }
        // // Crear un usuario administrador
        // User::create([
        //     'name' => 'Admin',
        //     'email' => 'admin@example.com',
        //     'password' => Hash::make('adminpassword'),
        //     'role_id' => 1, // Asignar el ID del rol de administrador
        // ]);

        // // Crear otros usuarios de ejemplo
        // User::factory()->count(5)->create();
    }
}
