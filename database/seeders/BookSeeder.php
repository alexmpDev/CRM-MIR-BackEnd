<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'title' => 'Harry Poter',
                'author' => 'J. K. Rowling',
                'isbn' => '9781234567897',
                'gender' => 'Magia'
            ],
            [
                'title' => ' Geronimo Stilton',
                'author' => 'Elisabetta Dami',
                'isbn' => '9781454567897',
                'gender' => 'Fantasia'
            ],
            // Puedes agregar más estudiantes aquí según sea necesario
        ];

        // Insertar los datos en la tabla 'Students'
        foreach ($books as $book) {
            DB::table('books')->insert($book);
        }
    }
}
