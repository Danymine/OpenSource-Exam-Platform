<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExerciceCloseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $domande = [
            [
                'materia' => 'Geografia',
                'testo' => 'Qual è la capitale dell\'Italia?',
                'opzioni' => [
                    'a' => 'Roma',
                    'b' => 'Parigi',
                    'c' => 'Madrid',
                    'd' => 'Berlino',
                ],
                'risposta_corretta' => 'a',
            ],
            [
                'materia' => 'Geografia',
                'testo' => 'Quanti continenti ci sono nel mondo?',
                'opzioni' => [
                    'a' => '5',
                    'b' => '7',
                    'c' => '10',
                    'd' => '6',
                ],
                'risposta_corretta' => 'b',
            ],
            [
                'materia' => 'Geografia',
                'testo' => 'Quale è il più grande oceano del mondo?',
                'opzioni' => [
                    'a' => 'Oceano Atlantico',
                    'b' => 'Oceano Indiano',
                    'c' => 'Oceano Pacifico',
                    'd' => 'Mar Mediterraneo',
                ],
                'risposta_corretta' => 'c',
            ],
            [
                'materia' => 'Scienze',
                'testo' => 'Quale pianeta è conosciuto come la "stella del mattino" o la "stella della sera"?',
                'opzioni' => [
                    'a' => 'Marte',
                    'b' => 'Venere',
                    'c' => 'Giove',
                    'd' => 'Saturno',
                ],
                'risposta_corretta' => 'b',
            ],
            [
                'materia' => 'Letteratura',
                'testo' => 'Chi scrisse "Romeo e Giulietta"?',
                'opzioni' => [
                    'a' => 'Charles Dickens',
                    'b' => 'Jane Austen',
                    'c' => 'William Shakespeare',
                    'd' => 'Leo Tolstoy',
                ],
                'risposta_corretta' => 'c',
            ],
            [
                'materia' => 'Storia',
                'testo' => 'In quale anno è iniziata la seconda guerra mondiale?',
                'opzioni' => [
                    'a' => '1939',
                    'b' => '1914',
                    'c' => '1945',
                    'd' => '1941',
                ],
                'risposta_corretta' => 'a',
            ],
            [
                'materia' => 'Anatomia',
                'testo' => 'Qual è l\'organo più grande del corpo umano?',
                'opzioni' => [
                    'a' => 'Cuore',
                    'b' => 'Polmoni',
                    'c' => 'Fegato',
                    'd' => 'Pelle',
                ],
                'risposta_corretta' => 'd',
            ],
            [
                'materia' => 'Arte',
                'testo' => 'Chi ha dipinto la \"Mona Lisa\"?',
                'opzioni' => [
                    'a' => 'Vincent van Gogh',
                    'b' => 'Leonardo da Vinci',
                    'c' => 'Pablo Picasso',
                    'd' => 'Michelangelo',
                ],
                'risposta_corretta' => 'b',
            ],
            [
                'materia' => 'Geografia',
                'testo' => 'Quale è il fiume più lungo del mondo?',
                'opzioni' => [
                    'a' => 'Nilo',
                    'b' => 'Amazzonia',
                    'c' => 'Mississippi',
                    'd' => 'Gange',
                ],
                'risposta_corretta' => 'a',
            ],
            [
                'materia' => 'Chimica',
                'testo' => 'Qual è l\'elemento chimico con simbolo "O"?',
                'opzioni' => [
                    'a' => 'Oro',
                    'b' => 'Ossigeno',
                    'c' => 'Azoto',
                    'd' => 'Idrogeno',
                ],
                'risposta_corretta' => 'b',
            ],
        ];
        $diff = [
            "Alta",
            "Media",
            "Bassa"
        ];

        for( $i = 0; $i < 10; $i++ ){

            DB::table('exercises')->insert([
                'user_id' => 1,
                'name' => $domande[$i]["testo"],
                'question' => $domande[$i]["testo"],
                'score' => rand(1,10),
                'difficulty' => $diff[rand(0,2)],
                'subject' => $domande[$i]["materia"],
                'type' => 'Risposta Multipla',
                'correct_option' => $domande[$i]["risposta_corretta"],
                'option_1' => $domande[$i]["opzioni"]['a'],
                'option_2' => $domande[$i]["opzioni"]['b'],
                'option_3' => $domande[$i]["opzioni"]['c'],
                'option_4' => $domande[$i]["opzioni"]['d']
            ]);
        }
    }
}
