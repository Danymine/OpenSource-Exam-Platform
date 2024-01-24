<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Inserisci un record nel database
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'jiganew437@ikuromi.com',
            'password' => Hash::make('password123'),
            'roles' => 'admin',
        ]);
    }
}
