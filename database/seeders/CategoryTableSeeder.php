<?php

namespace Database\Seeders;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name'=>'Personal Trainer',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        Category::create([
            'name'=>'Physiotherapist',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        Category::create([
            'name'=>'Coach',
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
    }
}
