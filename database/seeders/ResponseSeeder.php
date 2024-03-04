<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Creo di messaggi per il supporto");
        for( $i = 1; $i < 50; $i++ ){

            if( rand(false, true) ){

                DB::table('responses')->insert([
                    'response' => Str::random(rand(20, 255)),
                    'assistance_request_id' => $i,
                    'user_id' => $i,
                    'created_at' => now(),
                ]);
            }
        }

        $this->command->info("Terminato l'inserimento");
    }
}
