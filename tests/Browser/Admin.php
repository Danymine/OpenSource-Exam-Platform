<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use App\Models\User;
use Tests\DuskTestCase;

class Admin extends DuskTestCase
{
    /**
     * A basic browser test example.
     */

    public function testAdminCanSeeAddUser() : void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('roles', 'Admin')->first()->id)
                ->visit('/dashboard')
                ->press("#menu")
                ->clickLink('Aggiungi Utente')
                ->assertPathIs('/admin/add-user');
        });
    }

    public function testAdminCanAddStudent() : void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('roles', 'Admin')->first()->id)
                ->visit("/admin/add-user")
                ->type('name', 'Nome')
                ->type('first_name', 'Cognome')
                ->type('email', 'studente@example.com')
                ->type('password', 'password')
                ->select('roles', 'Student')
                ->press('Aggiungi Utente')
                ->assertPathIs('/admin/add-user')
                ->assertSee('Utente aggiunto con successo');
        });

        $this->assertDatabaseHas('users', [
            'name' => "Nome",
            "first_name" => "Cognome",
            'email' => 'studente@example.com',
            'email_verified_at' => NULL,
            'roles' => 'Student',
        ]);

    }

    public function testAdminCanAddTeacher() : void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('roles', 'Admin')->first()->id)
                ->visit("/admin/add-user")
                ->type('name', 'Nome')
                ->type('first_name', 'Cognome')
                ->type('email', 'docente@example.com')
                ->type('password', 'password')
                ->select('roles', 'Teacher')
                ->press('Aggiungi Utente')
                ->assertPathIs('/admin/add-user')
                ->assertSee('Utente aggiunto con successo');
        });

        $this->assertDatabaseHas('users', [
            'name' => "Nome",
            "first_name" => "Cognome",
            'email' => 'docente@example.com',
            'email_verified_at' => NULL,
            'roles' => 'Teacher',
        ]);
    }

    public function testAdminCanAddAdmin() : void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('roles', 'Admin')->first()->id)
                ->visit("/admin/add-user")
                ->type('name', 'Nome')
                ->type('first_name', 'Cognome')
                ->type('email', 'admin@example.com')
                ->type('password', 'password')
                ->select('roles', 'Admin')
                ->press('Aggiungi Utente')
                ->assertPathIs('/admin/add-user')
                ->assertSee('Utente aggiunto con successo');
        });

        $this->assertDatabaseHas('users', [
            'name' => "Nome",
            "first_name" => "Cognome",
            'email' => 'admin@example.com',
            'email_verified_at' => NULL,
            'roles' => 'Admin',
        ]);
    }

    public function testAdminCanSeeSearchUser() : void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('roles', 'Admin')->first()->id)
                ->visit('/dashboard')
                ->press("#menu")
                ->clickLink('Gestisci Utenti')
                ->assertPathIs('/admin/user-list');
        });
    }

    public function testAdminCanSeeResultSearchUser() : void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('roles', 'Admin')->first()->id)
                ->visit('/admin/user-list')
                ->type('email', "admin@example.com")
                ->press('Cerca')
                ->assertPathIs('/admin/search-user')
                ->assertSeeIn('.table', "admin@example.com");
        });
    }

    public function testAdminCanEditResultSearchUser() : void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('roles', 'Admin')->first()->id)
                ->visit('/admin/user-list')
                ->type('email', "admin@example.com")
                ->press('Cerca')
                ->click('#modify')
                ->pause(2000)
                ->assertVisible('#editModal')
                ->type('#name', "Nuovo Nome") // Modifica il nome
                ->type('#first_name', "Nuovo Cognome") // Modifica il cognome
                ->type('#edit_email', "newemailadmin@example.com") // Modifica l'email
                ->type('#date_birth', '01/01/1990') // Modifica la data di nascita
                ->select('#roles', "Teacher")
                ->press('Salva')
                ->assertPathIs('/admin/user-list');
        });

        $this->assertDatabaseHas('users', [
            'name' => "Nuovo Nome",
            "first_name" => "Nuovo Cognome",
            'email' => 'newemailadmin@example.com',
            'date_birth' => '1990-01-01',
            'email_verified_at' => NULL,
            'roles' => 'Teacher',
        ]);
    }

    public function testAdminCanEditResultSearchUserWithOneParamaters() : void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('roles', 'Admin')->first()->id)
                ->visit('/admin/user-list')
                ->type('email', "newemailadmin@example.com")
                ->press('Cerca')
                ->click('#modify')
                ->pause(2000)
                ->assertVisible('#editModal')
                ->type('#name', "Nuovo Nome 2") // Modifica il nome
                ->select('#roles', "Teacher")
                ->press('Salva')
                ->assertPathIs('/admin/user-list');
        });

        $this->assertDatabaseHas('users', [
            'name' => "Nuovo Nome 2",
            "first_name" => "Nuovo Cognome",
            'email' => 'newemailadmin@example.com',
            'date_birth' => '1990-01-01',
            'email_verified_at' => NULL,
            'roles' => 'Teacher',
        ]);
    }

    public function testAdminCanDeleteResultSearchUser() : void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::where('roles', 'Admin')->first()->id)
                ->visit('/admin/user-list')
                ->type('email', "newemailadmin@example.com")
                ->press('Cerca')
                ->pause(2000)
                ->click('.btn-danger')
                ->pause(2000)
                ->acceptDialog()
                ->pause(2000);
        });
        
        $deletedUser = User::where('email', 'newemailadmin@example.com')->first();
        $this->assertNull($deletedUser);
    }
 
}
