<?php

namespace Database\Seeders;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name'=>'admin',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        Role::create([
            'name'=>'trainer',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        Role::create([
            'name'=>'member',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
    }
}
