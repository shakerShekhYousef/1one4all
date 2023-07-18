<?php

namespace Database\Seeders;

use App\Models\SubCategory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SubcategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //first category
        SubCategory::create([
            'name'=>'Weight Loss',
            'category_id'=>1,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        SubCategory::create([
            'name'=>'Body Building',
            'category_id'=>1,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        SubCategory::create([
            'name'=>'Weight Gain',
            'category_id'=>1,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        SubCategory::create([
            'name'=>'Competition Preparation',
            'category_id'=>1,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        SubCategory::create([
            'name'=>'Sports and Conditioning',
            'category_id'=>1,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        SubCategory::create([
            'name'=>'Posture Correction',
            'category_id'=>1,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        //second category
        SubCategory::create([
            'name'=>'Rehabilitation',
            'category_id'=>2,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        SubCategory::create([
            'name'=>'Deep Tissue Massage',
            'category_id'=>2,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        //third category
        SubCategory::create([
            'name'=>'Calisthenics',
            'category_id'=>3,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        SubCategory::create([
            'name'=>'Gymnastics',
            'category_id'=>3,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        SubCategory::create([
            'name'=>'Tennis',
            'category_id'=>3,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        SubCategory::create([
            'name'=>'Gymnastics',
            'category_id'=>3,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        SubCategory::create([
            'name'=>'Football',
            'category_id'=>3,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        SubCategory::create([
            'name'=>'Basketball',
            'category_id'=>3,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);

        SubCategory::create([
            'name'=>'Swimming',
            'category_id'=>3,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);



    }
}
