<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validatedData = $request->validated();

        // Se è stata inviata un'immagine
        if ($request->hasFile('icon_profile')) {

            $file = $request->file('icon_profile');

            // Verifica se ci sono errori durante il caricamento del file
            if ($file->getError() == UPLOAD_ERR_OK) {

                $filename = time() . '_' . $file->getClientOriginalName();
                $location = 'uploads';

                $user = $request->user();

                if ($user->img_profile) {

                    unlink($user->img_profile);
                }

                $file->move(public_path($location), $filename);

                $user->img_profile = $location . '/' . $filename;
                $user->save();

            } else {

                return back()->withError('msg', "Problemi con il file");
            }
        }

        $user->fill($validatedData);

        if ($user->isDirty('email')) {
            
            $user->email_verified_at = null;
        }

        // Salva le modifiche
        $user->save();

        // Redirect alla pagina di modifica del profilo con un messaggio di successo
        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
