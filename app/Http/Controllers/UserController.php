<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Date;


class UserController extends Controller
{
    public function showAddUserForm()
    {
        return view('support-admin.aggiungi_utente'); 
    }

    public function store_user(Request $request)
    {
        // Validazione dei dati del form
        $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'roles' => 'required|in:Admin,Teacher,Student',
        ]);

        // Creazione dell'utente
        $user = new User([
            'name' => $request->input('name'),
            'first_name' => $request->input('first_name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'roles' => $request->input('roles'),
            'created_at' => now(),
        ]);

        // Salvataggio dell'utente nel database
        $user->save();

        return redirect()->back()->with('success', trans('Utente aggiunto con successo!'));
    }
    public function showUserList()
    {
        return view('support-admin.user-list');
    }

    public function update(Request $request, User $user)
    {
        //Diamo la possibilità di modificare tutto ma la password deve essere generata e inviata tramite email (SOLO SE L'EMAIL è verificata)
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|email',
            'roles' => 'required|in:Admin,Teacher,Student',
            'date_birth' => [
                'required',
                'nullable',
                'date',
                'before_or_equal:' . Date::now()->subYears(14)->format('Y-m-d'),
            ],
        ]);
        if ($validator->fails()) {
            return redirect()->route('user-list')
                        ->withErrors($validator);
        }
    
        $user = User::findOrFail($user->id);

        if (!$user) {
            return redirect()->route('user-list')
                        ->withErrors(['errors' => trans('Utente non trovato')]);
        }

        if( $user->email != $validator->validated()['email'] ){

            //è stata modificata l'email quindi oltre che ad aggiornare questo settiamo anche $user->email_verified_at a NULL così la prossima volta che effettua il login dovrà verificare l'email
            $user->update($validator->validated());
            $user->email_verified_at = NULL;
            $user->save();
        }
        else{

            $user->update($validator->validated());
        }

        return redirect()->route('user-list')->with('success', 'Utente aggiornato con successo');
    }
    
    public function search(Request $request)
    {
        $email = $request->input('email');
        $users = User::where('email', $email)->first();
        if( $users != NULL ){

            return view('support-admin.user-list', ['user' => $users]);
        }
        else{

            return redirect()->route('user-list')
                ->withErrors(['errors' => trans("Non esiste un account associato all'email inserita")]);

        }
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user-list')->with('success', trans('Utente eliminato con successo.'));
    }

    public function destroy_account(User $user){

        if( Auth::user()->id == $user->id ){

            $user->delete();
            Auth::logout();

            // Reindirizza l'utente alla home
            return redirect()->route('ciao');
        }
        else{

            return redirect()->route('user-list')
                ->withErrors(['errors' => trans("Non puoi cancellare questo account.")]);

        }
    }

}