<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Siswa Test',
            'email' => 'siswa@example.com',
            'role' => 'student',
        ]);

         \App\Models\User::factory()->create([
            'name' => 'Instruktur Test',
            'email' => 'instruktur@example.com',
            'role' => 'instructor',
         ]);

        \App\Models\User::factory()->create([
            'name' => 'Admin SkillUp',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
    }
}
