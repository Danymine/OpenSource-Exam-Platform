<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $domande = [
            [
                'materia' => 'Geografia',
                'testo' => 'Quale è la capitale dell\'Italia?',
                'opzioni' => [
                    'a' => 'Roma',
                    'b' => 'Parigi',
                    'c' => 'Madrid',
                    'd' => 'Berlino',
                ],
                'risposta_corretta' => 'a',
                'punteggio' => 5,
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
                'punteggio' => 7,
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
                'punteggio' => 6,
            ],
            [
                'materia' => 'Geografia',
                'testo' => 'In quale continente si trova l\'Australia?',
                'opzioni' => [
                    'a' => 'Asia',
                    'b' => 'Europa',
                    'c' => 'Africa',
                    'd' => 'Oceania',
                ],
                'risposta_corretta' => 'd',
                'punteggio' => 8,
            ],
            [
                'materia' => 'Geografia',
                'testo' => 'Qual è il deserto più grande del mondo?',
                'opzioni' => [
                    'a' => 'Deserto del Sahara',
                    'b' => 'Deserto del Gobi',
                    'c' => 'Deserto di Atacama',
                    'd' => 'Antartide',
                ],
                'risposta_corretta' => 'a',
                'punteggio' => 9,
            ],
            [
                'materia' => 'Geografia',
                'testo' => 'Quale è la catena montuosa più alta del mondo?',
                'opzioni' => [
                    'a' => 'Montagne Rocciose',
                    'b' => 'Alpi',
                    'c' => 'Himalaya',
                    'd' => 'Appalachi',
                ],
                'risposta_corretta' => 'c',
                'punteggio' => 7,
            ],
            [
                'materia' => 'Geografia',
                'testo' => 'In quale paese si trova il Kilimangiaro?',
                'opzioni' => [
                    'a' => 'Kenya',
                    'b' => 'Brasile',
                    'c' => 'Francia',
                    'd' => 'Russia',
                ],
                'risposta_corretta' => 'a',
                'punteggio' => 6,
            ],
            [
                'materia' => 'Geografia',
                'testo' => 'Quale è la capitale del Giappone?',
                'opzioni' => [
                    'a' => 'Pechino',
                    'b' => 'Bangkok',
                    'c' => 'Tokyo',
                    'd' => 'Seul',
                ],
                'risposta_corretta' => 'c',
                'punteggio' => 8,
            ],
            [
                'materia' => 'Geografia',
                'testo' => 'Qual è la più grande isola del mondo?',
                'opzioni' => [
                    'a' => 'Islanda',
                    'b' => 'Groenlandia',
                    'c' => 'Borneo',
                    'd' => 'Madagascar',
                ],
                'risposta_corretta' => 'b',
                'punteggio' => 7,
            ],
            [
                'materia' => 'Geografia',
                'testo' => 'Quale è il lago più profondo del mondo?',
                'opzioni' => [
                    'a' => 'Lago Superiore',
                    'b' => 'Lago Baikal',
                    'c' => 'Lago Vittoria',
                    'd' => 'Lago Titicaca',
                ],
                'risposta_corretta' => 'b',
                'punteggio' => 9,
            ],
            [
                'materia' => 'Geografia',
                'testo' => 'In quale oceano si trova l\'isola di Madagascar?',
                'opzioni' => [
                    'a' => 'Oceano Atlantico',
                    'b' => 'Oceano Indiano',
                    'c' => 'Oceano Pacifico',
                    'd' => 'Oceano Artico',
                ],
                'risposta_corretta' => 'b',
                'punteggio' => 8,
            ],
            [
                'materia' => 'Geografia',
                'testo' => 'Qual è la capitale del Canada?',
                'opzioni' => [
                    'a' => 'Toronto',
                    'b' => 'Vancouver',
                    'c' => 'Ottawa',
                    'd' => 'Montreal',
                ],
                'risposta_corretta' => 'c',
                'punteggio' => 6,
            ],
            [
                'materia' => 'Geografia',
                'testo' => 'Quale è il confine naturale tra Europa e Asia?',
                'opzioni' => [
                    'a' => 'Fiume Danubio',
                    'b' => 'Catena degli Urali',
                    'c' => 'Monti Carpazi',
                    'd' => 'Fiume Reno',
                ],
                'risposta_corretta' => 'b',
                'punteggio' => 7,
            ],
            [
                'materia' => 'Geografia',
                'testo' => 'Quale è la più alta cascata del mondo?',
                'opzioni' => [
                    'a' => 'Cascate del Niagara',
                    'b' => 'Angel Falls',
                    'c' => 'Cascate del Salto del Nervión',
                    'd' => 'Victoria Falls',
                ],
                'risposta_corretta' => 'b',
                'punteggio' => 8,
            ]
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
                'type' => 'Risposta Chiusa',
                'correct_option' => $domande[$i]["risposta_corretta"],
                'option_1' => $domande[$i]["opzioni"]['a'],
                'option_2' => $domande[$i]["opzioni"]['b'],
                'option_3' => $domande[$i]["opzioni"]['c'],
                'option_4' => $domande[$i]["opzioni"]['d']
            ]);
        }

    }
}
