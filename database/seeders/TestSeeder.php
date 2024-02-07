<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Exercise;
use App\Models\Practice;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private function generateKey()
    {
        $alphabet = array_merge(range('a', 'z'), range('A', 'Z'));
        $key = "";
        $found = false;

        do {
            for ($i = 0; $i < 6; $i++) {
                $rand = rand(0, 1);
                if ($rand == 0) {
                    $number = rand(0, 9);
                    $key .= $number;
                } else {
                    $char = rand(0, count($alphabet) - 1);
                    $key .= $alphabet[$char];
                }
            }

            $result = DB::table('practices')->where('key', $key)->get();
            if ($result->count() == 0) {
                $found = true;
            }
        } while (!$found);

        return $key;
    }

    public function run(): void
    {

        $titoli_esami = [
            'Analisi 1',
            'Analisi 2',
            'Discreta',
            'Algebra Lineare',
            'Geometria Differenziale',
            'Calcolo Numerico',
            'Fisica Matematica',
            'Teoria dei Numeri',
            'Probabilità e Statistica',
            'Equazioni Differenziali Ordinarie',
            'Analisi Funzionale',
            'Ottimizzazione Matematica',
            'Teoria dei Grafi',
            'Geometria Algebrica',
            'Logica Matematica',
            'Topologia',
            'Teoria delle Funzioni Complesse',
            'Combinatoria',
            'Teoria dei Codici',
            'Teoria dei Gruppi'
        ];
    
        $difficolty = [
            'Alta',
            'Media',
            'Bassa'
        ];

        $materie = [
            'Storia dell\'Arte',
            'Letteratura Italiana',
            'Chimica Organica',
            'Fisica Quantistica',
            'Economia Politica',
            'Filosofia Morale',
            'Storia Antica',
            'Scienze della Terra',
            'Psicologia Sociale',
            'Antropologia Culturale',
            'Biologia Molecolare',
            'Diritto Civile',
            'Educazione Fisica',
            'Informatica Avanzata',
            'Marketing Strategico',
            'Lingue Straniere',
            'Musica e Composizione',
            'Geopolitica Mondiale',
            'Architettura Moderna',
            'Medicina Interna'
        ];

        $type = [
            'Esame',
            'Esercitazione'
        ];

        for( $i = 0; $i < 50; $i++ ){

            $key = $this->generateKey();
            $total_score = rand(10, 100);
            $practice = new Practice([
                'title' => $titoli_esami[rand(0, count($titoli_esami)-1)],
                'description' => Str::random(15),
                'difficulty' => $difficolty[rand(0, count($difficolty)-1)],
                'subject' => $materie[rand(0, count($materie)-1)],
                'total_score' => $total_score,
                'key' => $key,
                'user_id' => 1,
                'feedback_enabled' => rand(0, 1),
                'randomize_questions' => rand(0, 1),
                'generated_at' => Carbon::now(),
                'allowed' => 0,
                'practice_date' => Carbon::now()->addDay(),
                'type' => $type[rand(0, 1)],
                'public' => 0
            ]);

            $practice->save();

            $result = DB::table('practices')->where('key', $key)->first();
            $exercises = Exercise::where('user_id', '=', 1)->get();

            // Modifica dei punteggi personalizzati degli esercizi nella pratica
            $sumScore = 0;

            foreach ($exercises as $exercise) {

                // Calcola il punteggio proporzionato per ciascun esercizio rispetto al max_score
                $customScore = round(($exercise->score / $total_score) * $total_score, 2);
                $sumScore += $customScore;
                $practice->exercises()->attach($exercise->id, ['custom_score' => $customScore]);
                if( $sumScore > $total_score ){

                    break;
                }
            }      
        }
    }
}
