<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            RoleSeeder::class,
            StressEventSeeder::class,
        ]);

        $adminRole = Role::where('libelle', 'Admin')->first();

        User::create([
            'name' => 'AdminCESI',
            'email' => 'admin@cesizen.fr',
            'password' => Hash::make('okokokok'), // Toujours hacher !
            'id_role' => $adminRole->id,
        ]);
    }
}
