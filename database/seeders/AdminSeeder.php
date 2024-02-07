<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'rixina1158@laymro.com',
            'password' => Hash::make('ciao1234'), 
            'roles'=> 'Admin',
        ]);
    }
}