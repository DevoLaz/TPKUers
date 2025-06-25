<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder lain yang Anda butuhkan di sini
        $this->call([
            KonveksiLengkapSeeder::class,
            // SafeDemoSeeder::class, // Anda bisa aktifkan jika perlu
            // TestingDataSeeder::class, // Atau seeder lainnya
        ]);

        // Anda juga bisa membuat user langsung di sini jika perlu
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Jika Anda ingin membuat 10 user random, uncomment baris di bawah
        // User::factory(10)->create();
    }
}