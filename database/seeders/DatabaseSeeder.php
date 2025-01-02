<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::create([
            'full_name' => 'Arman',
            'email' => 'arman@example.com',
            'role' => 'user',
            'password' => Hash::make('1234')
        ]);

        \App\Models\User::create([
            'full_name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('1234')
        ]);

        \App\Models\User::create([
            'full_name' => 'Arman Umar',
            'email' => 'ammangdeveloper@gmail.com',
            'role' => 'user',
            'password' => Hash::make('1234')
        ]);
        
        $this->call([CharacteristicSeeder::class, AssociatedTaskSeeder::class, FeatureTypeSeeder::class, SubjectAreaSeeder::class]);
    }
}
