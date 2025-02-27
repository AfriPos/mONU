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
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // add credit record
        \App\Models\Credit::create([
            'organization_id' => '67be14c8-411c',
            'balance' => 0,
        ]);

        // add routermodel seeder
        $this->call([
            RouterModelSeeder::class,
            RouterConfigurationSeeder::class,
            RolesAndPermissionsSeeder::class,
        ]);
    }
}
