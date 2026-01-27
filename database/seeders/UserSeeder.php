<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
   public function run()
{
    \App\Models\User::create([
        'name' => 'Administrateur',
        'email' => 'admin@idai.com',
        'password' => bcrypt('password123'),
        'role' => 'admin',
        'is_active' => 1
    ]);

    \App\Models\User::create([
        'name' => 'Responsable Technique',
        'email' => 'responsable@idai.com',
        'password' => bcrypt('password123'),
        'role' => 'responsable',
        'is_active' => 1
    ]);
}
}
