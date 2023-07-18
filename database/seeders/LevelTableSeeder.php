<?php

namespace Database\Seeders;

use App\Models\Level;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Level::create([
            'name'=>'Beginner',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);

        Level::create([
            'name'=>'Advanced',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);

        Level::create([
            'name'=>'Athlete',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);
    }
}
