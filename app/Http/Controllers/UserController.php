<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function showAddUserForm()
    {
        return view('Ordinare.aggiungi_utente'); 
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
    public function showUserList() //Deve essere migliorata
    {
        return view('Ordinare.user-list');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', trans('Utente eliminato con successo.'));
    }

    public function update(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'roles' => 'required|in:Admin,Teacher,Student',
            'date_birth' => 'required|nullable|date'
        ]);
    
        $user = User::where('email', $request->input('email'))->first();
    
        $user->update($validate);
    
        $user->save();
    
        return redirect()->route('user-list')->with('success', trans('Utente aggiornato con successo.'));
    }
    
    public function search(Request $request)
    {
        $email = $request->input('email');
        $users = User::where('email', $email)->first();
        if( $users != NULL ){

            return view('Ordinare.user-list', ['user' => $users]);
        }
        else{

            return back()->with('error', trans("Non esiste un account associato all'email inserita"));
        }
    }

    public function destroy_account(User $user){

        if( Auth::user()->id == $user->id ){

            $user->delete();
            Auth::logout();

            // Reindirizza l'utente alla home
            return redirect()->route('ciao');
        }
        else{

            return back()->withErrors('error', trans("Non puoi cancellare questo account."));
        }
    }

}