<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Practice;
use App\Models\Exercise;
use Illuminate\Support\Facades\DB;

class PracticeController extends Controller
{
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

    public function generatePracticeWithFilters(Request $request)
    {
        $title = $request->input('title');
        $description = $request->input('description');
        $difficulty = $request->input('difficulty');
        $subject = $request->input('subject');
        $maxQuestions = $request->input('max_questions');
        $maxScore = $request->input('max_score');

        $exerciseQuery = Exercise::query();

        if ($difficulty) {
            $exerciseQuery->where('difficulty', $difficulty);
        }

        if ($subject) {
            $exerciseQuery->where('subject', $subject);
        }

        $filteredExercises = $exerciseQuery->get();
        $totalScore = $filteredExercises->sum('score');

        if ($maxScore && $totalScore > $maxScore) {
            return redirect()->back()->with('error', 'La somma dei punteggi supera il massimo consentito.');
        }

        if ($maxQuestions) {
            $filteredExercises = $filteredExercises->take($maxQuestions);
        }

        // Calcola la somma degli score dei filtri e calcola il rapporto per il totale richiesto
        $totalScoreFiltered = $filteredExercises->sum('score');
        $scoreRatio = $totalScore ? $totalScore / $totalScoreFiltered : 1;

        // Proporziona gli score degli esercizi
        foreach ($filteredExercises as $exercise) {
            $exercise->score *= $scoreRatio;
        }

        $newPractice = Practice::create([
            'title' => $title,
            'description' => $description,
            'difficulty' => $difficulty,
            'subject' => $subject,
            'total_score' => $totalScore,
        ]);

        foreach ($filteredExercises as $exercise) {
            $exercise->practice()->associate($newPractice);
            $exercise->save();
        }

        $key = $this->generateKey();

        return view('practice_new')->with('newPractice', $newPractice)->with('key', $key);
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

    public function show(Practice $practice)
    {
        return view('practice_show', ['practice' => $practice]);
    }

    public function edit(Practice $practice)
    {
        return view('practice_edit', ['practice' => $practice]);
    }

    public function update(Request $request, Practice $practice)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'difficulty' => 'required',
            'subject' => 'required',
            'total_score' => 'required',
        ]);

        $practice->update($validatedData);

        return redirect()->route('practices.show', $practice->id)->with('success', 'Practice updated successfully');
    }

    public function destroy(Practice $practice)
    {
        // Iniziare una transazione per assicurarsi che l'operazione sia atomica
        \DB::beginTransaction();

        try {
            // Elimina gli esercizi associati alla pratica
            $practice->exercises()->delete();

            // Ora elimina la pratica stessa
            $practice->delete();

            // Commit della transazione se tutto va bene
            \DB::commit();

            return redirect()->route('practices.index')->with('success', 'Practice deleted successfully');
        } catch (\Exception $e) {
            // Rollback della transazione in caso di errore
            \DB::rollback();
            return redirect()->back()->with('error', 'Error deleting practice');
        }
    }

}