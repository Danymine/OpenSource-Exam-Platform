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
        return view('user_list', compact('users'));
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return redirect()->back()->with('success', 'Utente eliminato con successo!');
        } else {
            return redirect()->back()->with('error', 'Utente non trovato!');
        }
    }


    public function editUserForm($id)
    {
        $user = User::findOrFail($id);
        return view('edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        // Altre eventuali modifiche ai campi dell'utente
        $user->save();

        return redirect()->route('users-list')->with('success', 'Profilo aggiornato con successo.');
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

}