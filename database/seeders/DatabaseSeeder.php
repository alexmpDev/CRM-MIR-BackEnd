<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CoursesTableSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(StudentsTableSeeder::class);
        $this->call(BookSeeder::class);
        $this->call(ReservationSeeder::class);
        $this->call(WcPassSeeder::class);
        $this->call(PhoneInfoSeeder::class);
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
  
    }
}
