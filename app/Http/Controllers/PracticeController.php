<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Practice;
use App\Models\Exercise;

class PracticeController extends Controller
{
    public function generatePracticeWithFilters(Request $request)
    {
        // Recupera i filtri
        $title = $request->input('title');
        $description = $request->input('description');
        $difficulty = $request->input('difficulty');
        $subject = $request->input('subject');
        $maxQuestions = $request->input('max_questions');
        $maxScore = $request->input('max_score');

        // Query per filtrare gli esercizi
        $exerciseQuery = Exercise::query();

        if ($difficulty) {
            $exerciseQuery->where('difficulty', $difficulty);
        }

        if ($subject) {
            $exerciseQuery->where('subject', $subject);
        }

        // Esegue la query per ottenere gli esercizi filtrati
        $filteredExercises = $exerciseQuery->get();

        // Calcola il punteggio totale degli esercizi
        $totalScore = $filteredExercises->sum('score');

        // Verifica se il punteggio totale degli esercizi supera il punteggio massimo consentito
        if ($maxScore && $totalScore > $maxScore) {
            return redirect()->back()->with('error', 'La somma dei punteggi supera il massimo consentito.');
        }

        // Limita il numero di domande se specificato
        if ($maxQuestions) {
            $filteredExercises = $filteredExercises->take($maxQuestions);
        }

        // Crea una nuova esercitazione con i filtri specificati utilizzando il metodo fill() e save()
        $newPractice = new Practice();
        $newPractice->fill([
            'title' => $title,
            'description' => $description,
            'difficulty' => $difficulty,
            'subject' => $subject,
            'total_score' => $totalScore,
        ]);
        $newPractice->save();

        // Associa gli esercizi filtrati all'esercitazione appena creata
        foreach ($filteredExercises as $exercise) {
            $exercise->practice()->associate($newPractice); // Correzione della chiamata a practice()
            $exercise->save();
        }

        // Dopo aver creato la pratica
        return view('practice_new')->with('newPractice', $newPractice);
    }

    public function create()
    {
        return view('practice_create');
    }

    public function index()
    {
        $practices = Practice::all();
        return view('practices', ['practices' => $practices]);
    }
}
