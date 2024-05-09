<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
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
