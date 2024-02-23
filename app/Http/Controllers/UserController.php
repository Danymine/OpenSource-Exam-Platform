<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function showAddUserForm()
    {
        return view('aggiungi_utente'); 
    }

    public function aggiungiUtente(Request $request)
    {
        // Validazione dei dati del form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'roles' => 'required|in:admin,Teacher,Student',
        ]);

        // Creazione dell'utente
        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'roles' => $request->input('roles'),
        ]);

        // Salvataggio dell'utente nel database
        $user->save();

        return redirect()->back()->with('success', 'Utente aggiunto con successo!');
    }
    public function showUserList()
    {
        $users = User::all();
        return view('user-list', compact('users'));
    }

    public function destroy($id)
    {
        $user = User::find($id);
    
        if ($user) {
            $user->delete();
            return redirect()->back()->with('success', 'Utente eliminato con successo.');
        } else {
            return redirect()->back()->with('error', 'Utente non trovato.');
        }
    }
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'roles' => 'required|in:Studente,Amministratore,Professore',
        ]);
    
        $user = User::findOrFail($id);
    
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->roles = $request->input('roles');
    
        $user->save();
    
        return redirect()->route('user-list')->with('success', 'Utente aggiornato con successo.');
    }

    public function editUserForm($id)
    {
        $user = User::findOrFail($id);
        return view('edit', compact('user'));
    }


    public function cancelEdit()
    {
        return redirect()->route('users-list');
    }
    public function showUserListFromDb()
    {
        $users = User::all();
        return view('user-list', compact('users'));
    }

    public function search(Request $request)
    {
        $email = $request->input('email');
        $users = User::where('email', 'like', "%$email%")->get();

        return view('user-list', ['users' => $users]);
    }

    public function destroy_account(User $user){

        if( Auth::user()->id == $user->id ){

            $user->delete();
            Auth::logout();

            // Reindirizza l'utente alla home
            return redirect()->route('ciao');
        }
        else{

            return back()->withErrors('msg',"Non puoi cancellare questo account.");
        }
    }

}