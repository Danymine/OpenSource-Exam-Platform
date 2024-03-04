<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Creo delle richieste di supporto");
        for( $i = 0; $i < 50; $i++ ){

            DB::table('assistance_requests')->insert([
                'subject' => Str::random(5) . $i + 1,
                'description' => Str::random('255'),
                'status' => rand(0,1),
                'user_id' => rand(1, 98),
                'admin_id'=> 102,
                'created_at' => now(),
            ]);
        }

        $this->command->info("Terminato l'inserimento");
    }
}
