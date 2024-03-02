<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $this->command->info("Creo un Amministratore con le seguenti credenziali. Email: admin@admin.com Password: admin");
        DB::table('users')->insert([
            'name' => 'Admin',
            'first_name' => 'Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('admin'),
            'roles'=> 'Admin',
            'date_birth' => '2001-07-13'
        ]);
        $this->command->info("La creazione è andata a buon fine.");
    }
}