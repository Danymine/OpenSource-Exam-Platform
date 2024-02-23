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
        $possibleTotalScores = [10, 30, 100];
        $difficulties = ['Alta', 'Media', 'Bassa'];
        $type = ['Exam', 'Practice'];
    
        // Ottenere un elenco di materie uniche dalla tabella exercises
        $subjects = Exercise::pluck('subject')->unique()->toArray();
    
        for ($i = 0; $i < 50; $i++) {
            $key = $this->generateKey();
            $totalScore = $possibleTotalScores[array_rand($possibleTotalScores)];
            $difficulty = $difficulties[array_rand($difficulties)];
            $typeIndex = array_rand($type);
    
            // Seleziona casualmente una materia tra quelle presenti nel database
            $subject = $subjects[array_rand($subjects)];
    
            $practice = new Practice([
                'title' => 'Titolo prova', // Puoi inserire un titolo statico o generare casualmente un titolo
                'description' => $subject, // Imposta la descrizione come il nome della materia
                'difficulty' => $difficulty,
                'subject' => $subject,
                'total_score' => $totalScore,
                'key' => $key,
                'user_id' => 5,
                'feedback_enabled' => rand(0, 1),
                'randomize_questions' => rand(0, 1),
                'generated_at' => Carbon::now(),
                'allowed' => 0,
                'practice_date' => Carbon::now()->addDay(),
                'type' => $type[$typeIndex],
                'public' => 0
            ]);
    
            $practice->save();
    
            // Modifica dei punteggi personalizzati degli esercizi nella pratica
            $sumScore = 0;
            $exerciseCount = 0;
    
            // Ottenere gli esercizi corrispondenti alla materia selezionata
            $exercises = Exercise::where('subject', $subject)->get();
    
            foreach ($exercises as $exercise) {
                // Assicurati di non aggiungere troppi esercizi alla pratica
                if ($exerciseCount >= 10) {
                    break;
                }
    
                // Calcola il punteggio proporzionato per ciascun esercizio rispetto al max_score
                $customScore = round(($exercise->score / $totalScore) * $totalScore, 2);
                $sumScore += $customScore;
                $practice->exercises()->attach($exercise->id, ['custom_score' => $customScore]);
                
                // Incrementa il contatore degli esercizi aggiunti
                $exerciseCount++;
    
                if ($sumScore > $totalScore) {
                    break;
                }
            }      
        }
    }    
}
