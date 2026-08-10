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
        $faker = \Faker\Factory::create('fr_FR');

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
        $user1 = \App\Models\User::create([
            'name' => 'Ingénieur Réseau',
            'email' => 'user@datacenter.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $userIds = [$responsable->id, $user1->id];

        // Générer 50 utilisateurs supplémentaires
        for ($i = 0; $i < 50; $i++) {
            $user = \App\Models\User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password'),
                'role' => $faker->randomElement(['user', 'responsable']),
                'is_active' => true,
            ]);
            $userIds[] = $user->id;
        }

        $resourceIds = [];
        $types = ['Serveur', 'Stockage', 'Réseau', 'Base de données', 'Sécurité'];
        $categories = ['Physique', 'Virtuelle'];
        
        // Générer 150 ressources
        for ($i = 0; $i < 150; $i++) {
            $name = $faker->randomElement(['Serveur Dell', 'Switch Cisco', 'Routeur Juniper', 'Baie NetApp', 'Firewall Fortinet']);
            $img = null;
            if (strpos($name, 'Serveur Dell') !== false) $img = 'serveur_dell.png';
            if (strpos($name, 'Switch Cisco') !== false) $img = 'switch_cisco.png';
            if (strpos($name, 'Baie NetApp') !== false) $img = 'baie_netapp.png';
            if (strpos($name, 'Firewall Fortinet') !== false) $img = 'firewall_fortinet.png';
            if (strpos($name, 'Routeur Juniper') !== false) $img = 'switch_cisco.png';

            $res = \App\Models\Resource::create([
                'name' => $name . ' ' . $faker->bothify('??-####'),
                'type' => $faker->randomElement($types),
                'category' => $faker->randomElement($categories),
                'cpu' => $faker->randomElement([4, 8, 16, 32, 64]),
                'ram' => $faker->randomElement([16, 32, 64, 128, 256]),
                'storage_capacity' => $faker->randomElement([500, 1000, 2000, 5000, 10000]),
                'storage_type' => $faker->randomElement(['SSD', 'HDD', 'NVMe', 'SAN']),
                'bandwidth' => $faker->randomElement(['1 Gbps', '10 Gbps', '25 Gbps', '40 Gbps']),
                'os' => $faker->randomElement(['Ubuntu 22.04', 'Windows Server 2022', 'Debian 11', 'CentOS 9', 'VMware ESXi']),
                'location' => 'Baie ' . $faker->numberBetween(1, 50) . ' - U' . $faker->numberBetween(1, 42),
                'image' => $img ? 'images/resources/' . $img : null,
                'status' => $faker->randomElement(['disponible', 'en_maintenance', 'reserve']),
                'manager_id' => $responsable->id,
            ]);
            $resourceIds[] = $res->id;
        }

        // Générer 300 réservations
        for ($i = 0; $i < 300; $i++) {
            $start = $faker->dateTimeBetween('-3 months', '+2 months');
            $end = (clone $start)->modify('+' . $faker->numberBetween(1, 30) . ' days');
            $status = $faker->randomElement(['en_attente', 'approuvee', 'rejetee', 'terminee']);
            
            \App\Models\Reservation::create([
                'user_id' => $faker->randomElement($userIds),
                'resource_id' => $faker->randomElement($resourceIds),
                'start_date' => $start,
                'end_date' => $end,
                'status' => $status,
                'justification' => $faker->sentence($faker->numberBetween(5, 15)),
                'rejection_reason' => $status === 'rejetee' ? $faker->sentence() : null,
            ]);
        }
        
        // Générer quelques incidents (utilisant DB s'il n'y a pas de modèle)
        for ($i = 0; $i < 50; $i++) {
            \Illuminate\Support\Facades\DB::table('incidents')->insert([
                'resource_id' => $faker->randomElement($resourceIds),
                'user_id' => $faker->randomElement($userIds),
                'subject' => $faker->sentence(),
                'description' => $faker->paragraph(),
                'status' => $faker->randomElement(['ouvert', 'resolu']),
                'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}