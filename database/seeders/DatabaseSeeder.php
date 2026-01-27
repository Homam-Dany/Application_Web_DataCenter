<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
{
    // 1. Création de l'Administrateur
    \App\Models\User::create([
        'name' => 'Admin Système',
        'email' => 'admin@datacenter.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'is_active' => true,
    ]);

    // 2. Création du Responsable Technique
    $responsable = \App\Models\User::create([
        'name' => 'Responsable Tech',
        'email' => 'responsable@datacenter.com',
        'password' => bcrypt('password'),
        'role' => 'responsable',
        'is_active' => true,
    ]);

    // 3. Création d'un Utilisateur Interne
    \App\Models\User::create([
        'name' => 'Ingénieur Réseau',
        'email' => 'user@datacenter.com',
        'password' => bcrypt('password'),
        'role' => 'user',
        'is_active' => true,
    ]);

    // 4. Création d'une ressource de test
    \App\Models\Resource::create([
        'name' => 'Serveur Dell PowerEdge R740',
        'type' => 'Serveur',
        'category' => 'Physique',
        'cpu' => 32,
        'ram' => 128,
        'os' => 'Ubuntu Server 22.04',
        'status' => 'disponible',
        'manager_id' => $responsable->id,
    ]);
}
}