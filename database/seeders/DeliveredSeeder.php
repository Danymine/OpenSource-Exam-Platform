<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Delivered;
use App\Models\Practice;
use App\Models\Answer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class DeliveredSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $this->command->info("Inizio inserendo 100 utenti.");
        for( $i = 0; $i < 100;  $i++ ){

            DB::table('users')->insert([
                'name' => "Nome" . $i + 1,
                'first_name' => "Cognome" . $i + 1,
                'email' => 'pseudoemail_' . Str::random(5) . $i + 1 . '@example.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('ciao1234'), 
                'roles'=> 'Student',
            ]);
        }
        $this->command->info("Terminato l'inserimento");

        $this->command->info("Incomincio l'inserimento delle consegne da parte degli utenti.");

        $practices = Practice::where('user_id', '=' , 1)->with('exercises')->get();
        $users = User::all();
        
        foreach( $practices as $practice ){

            if( rand(true, false) ){  //Decide se devo inserire qualcosa o meno.

                switch( rand(0, 2) ){

                    case 0: //Non valuto Nulla

                        for( $i = 2; $i < rand(2, 25); $i++ ){

                            $delivered = new Delivered([
                                'user_id' => $users[$i]->id,
                                'practice_id' => $practice->id,
                                'created_at' => Carbon::now(),
                                'valutation' => NULL,
                                'note' => NULL,
                                'path' => NULL,
                            ]);
    
                            $delivered->save();
    
                            foreach( $practice["exercises"] as $exercise){
    
                                if( $exercise->type == "Risposta Aperta" ){
    
                                    $response = Str::random(20);
                                }
                                else if ( $exercise->type == "Risposta Multipla" ){
    
                                    switch(rand(1, 4)){
    
                                        case 1:
                                            $response = $exercise->option_1;
                                        break;
    
                                        case 2:
                                            $response = $exercise->option_2;
                                        break;
    
                                        case 3:
                                            $response = $exercise->option_3;
                                        break;
    
                                        case 4:
                                            $response = $exercise->option_4;
                                        break;
                                    }
                                }
                                else{
    
                                    $condizione = rand(true, false);
                                    $response = $condizione ? 'vero' : 'falso';
                                }
    
                                $answer = new Answer([
                                    'delivered_id' => $delivered->id,
                                    'exercise_id' => $exercise->id,
                                    'response' => $response,
                                ]);

                                $answer->save();
                            }
                        }
                    break;

                    case 1: //Valuto qualcosa.

                        for( $i = 2; $i < rand(2, 25); $i++ ){

                            if( rand(true, false) ){

                                $delivered = new Delivered([
                                    'user_id' => $users[$i]->id,
                                    'practice_id' => $practice->id,
                                    'created_at' => Carbon::now(),
                                    'valutation' => rand(1, $practice->total_score),
                                    'note' => Str::random(10),
                                ]);
        
                                $delivered->save();

                                foreach( $practice["exercises"] as $exercise){

                                    if( $exercise->type == "Risposta Aperta" ){
        
                                        $response = Str::random(20);
                                    }
                                    else if ( $exercise->type == "Risposta Multipla" ){
        
                                        switch(rand(1, 4)){
        
                                            case 1:
                                                $response = $exercise->option_1;
                                            break;
        
                                            case 2:
                                                $response = $exercise->option_2;
                                            break;
        
                                            case 3:
                                                $response = $exercise->option_3;
                                            break;
        
                                            case 4:
                                                $response = $exercise->option_4;
                                            break;
                                        }
                                    }
                                    else{
        
                                        $condizione = rand(true, false);
                                        $response = $condizione ? 'vero' : 'falso';
                                    }
        
                                    $answer = new Answer([
                                        'delivered_id' => $delivered->id,
                                        'exercise_id' => $exercise->id,
                                        'response' => $response,
                                        'score_assign' => rand(0, $exercise->score),
                                        'note'  => Str::random(10),
                                    ]);

                                    $answer->save();
                                }
                            }
                            else{

                                $delivered = new Delivered([
                                    'user_id' => $users[$i]->id,
                                    'practice_id' => $practice->id,
                                    'created_at' => Carbon::now(),
                                    'valutation' => NULL,
                                    'note' => NULL,
                                    'path' => NULL,
                                ]);
        
                                $delivered->save();
        
                                foreach( $practice["exercises"] as $exercise){
        
                                    if( $exercise->type == "Risposta Aperta" ){
        
                                        $response = Str::random(20);
                                    }
                                    else if ( $exercise->type == "Risposta Multipla" ){
        
                                        switch(rand(1, 4)){
        
                                            case 1:
                                                $response = $exercise->option_1;
                                            break;
        
                                            case 2:
                                                $response = $exercise->option_2;
                                            break;
        
                                            case 3:
                                                $response = $exercise->option_3;
                                            break;
        
                                            case 4:
                                                $response = $exercise->option_4;
                                            break;
                                        }
                                    }
                                    else{
        
                                        $condizione = rand(true, false);
                                        $response = $condizione ? 'vero' : 'falso';
                                    }
        
                                    $answer = new Answer([
                                        'delivered_id' => $delivered->id,
                                        'exercise_id' => $exercise->id,
                                        'response' => $response,
                                    ]);

                                    $answer->save();
                                }
                            }
                        }
                    break;
                    
                    case 2: //Valuto tutto.

                        for( $i = 2; $i < rand(2, 25); $i++ ){

                            $delivered = new Delivered([
                                'user_id' => $users[$i]->id,
                                'practice_id' => $practice->id,
                                'created_at' => Carbon::now(),
                                'valutation' => rand(1, $practice->total_score),
                                'note' => Str::random(10),
                            ]);
    
                            $delivered->save();

                            foreach( $practice["exercises"] as $exercise){

                                if( $exercise->type == "Risposta Aperta" ){
    
                                    $response = Str::random(20);
                                }
                                else if ( $exercise->type == "Risposta Multipla" ){
    
                                    switch(rand(1, 4)){
    
                                        case 1:
                                            $response = $exercise->option_1;
                                        break;
    
                                        case 2:
                                            $response = $exercise->option_2;
                                        break;
    
                                        case 3:
                                            $response = $exercise->option_3;
                                        break;
    
                                        case 4:
                                            $response = $exercise->option_4;
                                        break;
                                    }
                                }
                                else{
    
                                    $condizione = rand(true, false);
                                    $response = $condizione ? 'vero' : 'falso';
                                }
    
                                $answer = new Answer([
                                    'delivered_id' => $delivered->id,
                                    'exercise_id' => $exercise->id,
                                    'response' => $response,
                                    'score_assign' => rand(0, $exercise->score),
                                    'note'  => Str::random(10),
                                ]);

                                $answer->save();
                            }

                            $practice->public = 1;
                            $practice->save();
                        }
                    break;
                }
            }
        }
        
        $this->command->info("Terminato l'inserimento");
    }
}
