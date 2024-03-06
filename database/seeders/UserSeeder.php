<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Inserisco 100 utenti. (Vi è sempre un Account docente disponibile con le seguenti credenziali: Email docente@docente.com Password: docente)");
        DB::table('users')->insert([
            'name' => "NomeDocente",
            'first_name' => "CognomeDocente",
            'email' => "docente@docente.com",
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('docente'), 
            'roles'=> "Teacher"
        ]);
        $this->command->info("Account studente disponibile con le seguenti credenziali: Email studente@studente.com Password: studente");
        DB::table('users')->insert([
            'name' => "NomeStudente",
            'first_name' => "CognomeStudente",
            'email' => "studente@studente.com",
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('studente'), 
            'roles'=> "Student"
        ]);
        for( $i = 0; $i < 100;  $i++ ){

            DB::table('users')->insert([
                'name' => "Nome" . $i + 1,
                'first_name' => "Cognome" . $i + 1,
                'email' => 'pseudoemail_' . Str::random(5) . $i + 1 . '@example.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('ciao1234'), 
                'roles'=> "Student"
            ]);
        }
        $this->command->info("Terminato l'inserimento");
    }
}
