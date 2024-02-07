<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrazioneTest extends TestCase
{
    /**
     * A basic test example.
     */
    use RefreshDatabase;

    /**
     * Testa la registrazione di un utente.
     */
    public function test_registrazione_utente(): void
    {
        // Simula la richiesta di registrazione inviando i dati del modulo
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'roles' => 'Student',
        ]);

        // Verifica che la registrazione sia avvenuta con successo
        $response->assertStatus(302); // Verifica il reindirizzamento dopo la registrazione
        $this->assertAuthenticated(); // Verifica che l'utente sia autenticato dopo la registrazione
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'roles' => 'Student',
        ]); // Verifica che l'utente sia stato memorizzato nel database
    }
}
