<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([UsersSeeder::class]);
        $this->call([RoleTableSeeder::class]);
        $this->call([CategoryTableSeeder::class]);
        $this->call([SubcategoryTableSeeder::class]);
        $this->call([LevelTableSeeder::class]);
        $this->call([CurrencyTableSeeder::class]);
    }
}
