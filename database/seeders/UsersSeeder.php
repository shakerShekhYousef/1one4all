<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::create([
            'name' =>'admin',
            'email' => 'admin@1one4all.com',
            'mobile' => '+963111111',
            'age' => '32',
            'password' => Hash::make('123456789'),
            'role_id' => '1',
            'bio' => 'it is big test data for',
            'level_id' => 2,
            'profile_pic' => 'user.png'
        ]);

        User::create([
            'name' =>'trainer1',
            'email' => 'trainer1@1one4all.com',
            'mobile' => '+963111112',
            'age' => '30',
            'password' => Hash::make('123456789'),
            'role_id' => 2,
            'subcategory_id'=>13,
            'bio' => 'it is big test data for',
            'approved' => false,
            'level_id' => 2,
            'profile_pic' => 'user.png'
        ]);

        User::create([
            'name' =>'player1',
            'email' => 'player1@1one4all.com',
            'mobile' => '+963111113',
            'age' => '31',
            'password' => Hash::make('123456789'),
            'role_id' => '3',
            'bio' => 'it is big test data for',
            'level_id' => 1,
            'profile_pic' => 'user.png'
        ]);

        User::create([
            'name' =>'player2',
            'email' => 'player2@1one4all.com',
            'mobile' => '+963111114',
            'age' => '33',
            'password' => Hash::make('123456789'),
            'role_id' => '3',
            'bio' => 'it is big test data for',
            'level_id' => 1
        ]);

        User::create([
            'name' =>'player3',
            'email' => 'player3@1one4all.com',
            'mobile' => '+963111115',
            'age' => '33',
            'password' => Hash::make('123456789'),
            'role_id' => '3',
            'bio' => 'it is big test data for',
            'level_id' => 3
        ]);

        User::create([
            'name' =>'player4',
            'email' => 'player4@1one4all.com',
            'mobile' => '+963111116',
            'age' => '34',
            'password' => Hash::make('123456789'),
            'role_id' => 3,
            'bio' => 'it is big test data for',
            'level_id' => 2
        ]);
        \App\Models\User::factory()->count(100)->create();
    }
}
