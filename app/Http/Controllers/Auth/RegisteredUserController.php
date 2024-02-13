<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'min:8', 'max:255', 'regex:/^(?!.*[_.-]{2})[a-zA-Z0-9_.-]{4,}@(?!-)(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/', 'unique:'.User::class],
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d\W]+$/',
            ],            
            'role' => ['required', 'string', Rule::in(['Student', 'Teacher'])],
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles' => $request->input('role')
        ]);

        //Lancia un evento di un utente correttamente registrato e andrà a cercare i, o il providers, che si occupa di ascoltare quell'evento. Automatiamente larvael aggiunge listener al nome RegisterdListener
        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
