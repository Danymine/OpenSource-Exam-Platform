<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class Studenti extends DuskTestCase
{
    /**
     * A Dusk test example.
    */

    public function testStudentCanRegister() : void
    {

        $email = "Prova@example.com"; 

        $this->browse(function (Browser $browser) use ($email) {

            $browser->visit('/register')
                    ->type('name', 'Prova')
                    ->type('firstname', 'Cognome')
                    ->type('date_birth', '07/13/2001')
                    ->type('email', $email)
                    ->type('password', 'Password123')
                    ->type('password_confirmation', 'Password123')
                    ->radio('role', 'Student')
                    ->click('button[type="submit"]')
                    ->waitForLocation('/verify-email')
                    ->assertPathIs('/verify-email');
        });

        $user = User::where('email', $email)->first();
        $user->markEmailAsVerified();

        $this->assertNotNull($user);
        $this->assertEquals('Prova', $user->name);
        $this->assertEquals('Cognome', $user->first_name);
        $this->assertEquals('2001-07-13', $user->date_birth);
        $this->assertEquals('Prova@example.com', $user->email);
        $this->assertEquals('Student', $user->roles);
    }

    
    public function testStudentCanLogout(){

        $this->browse(function (Browser $browser) {

            $browser->visit('/dashboard')
                ->click('#menu-profile')
                ->waitFor('#menu-profile')
                ->clickLink('Esci')
                ->assertPathIs('/');
         });
    }

   
    public function testStudentCanLogin() : void
    {

        $email = Str::random(10) . "@example.com";
        $user = new User([
            'name' => 'Studente',
            'first_name' => 'Cognome',
            'date_birth' => '2001-07-13',
            'email' => $email,
            'password' => Hash::make("Password123"),
            'roles' => 'Student',
            'email_verified_at' => now(),
        ]);
        $user->markEmailAsVerified();
        $user->save();
        
        $this->browse(function (Browser $browser) use ($email) {

            $browser->visit('/login')
                ->type('email', $email)
                ->type('password', "Password123")
                ->click('button[type="submit"]')
                ->assertPathIs('/dashboard');
        });
    }

    //Modifica dati personali.
    public function testStudentCanSeeProfile() : void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/dashboard')
                ->click('#menu-profile')
                ->waitFor('#menu-profile')
                ->clickLink('Profilo')
                ->assertPathIs('/profile');
        });
    }
    
    public function testStudentCanEditNameCorrect() : void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/profile')
                ->type('name', "CambioNome")
                ->click('#save')
                ->assertPathIs('/profile');
        });

        $user = User::latest()->first();
        
        $this->assertEquals('CambioNome', $user->name);
    }

    public function testStudentCanEditSurnameCorrect() : void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/profile')
                ->type('first_name', "CambioCognome")
                ->click('#save')
                ->assertPathIs('/profile');
        });

        $user = User::latest()->first();
        
        $this->assertEquals('CambioCognome', $user->first_name);
    }

    public function testStudentCanEditDateBirthCorrect() : void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/profile')
                ->screenshot('prima')
                ->type('date_birth', "07/14/2001")
                ->click('#save')
                ->assertPathIs('/profile')
                ->screenshot('dopo');
        });

        $user = User::latest()->first();
        
        $this->assertEquals('2001-07-14', $user->date_birth);
    }

    public function testInvalidEmailWithoutAtSymbol() : void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/profile')
                ->type('email', "invalid_email.com")
                ->click('#save')
                ->assertPathIs('/profile');
        });

        $user = User::latest()->first();

        $this->assertNotNull($user->email_verified_at);
        $this->assertNotEquals('invalid_email.com', $user->email);

    }

    /*
    public function testInvalidEmailWithoutDomain() : void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/profile')
                ->type('email', "invalid_email@")
                ->click('#save')
                ->assertPathIs('/profile');
        });

        $user = User::latest()->first();

        $this->assertNotNull($user->email_verified_at);
        $this->assertNotEquals('invalid_email@', $user->email);
    }

    public function testInvalidEmailWithoutTLD() : void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/profile')
                ->type('email', "invalid_email@example")
                ->click('#save')
                ->assertPathIs('/profile')
                ->assertVisible('#error');
        });

        $user = User::latest()->first();

        $this->assertNotNull($user->email_verified_at);
        $this->assertNotEquals('invalid_email@example', $user->email);
    }

    public function testInvalidEmailWithSpecialCharacters() : void
    {

        $this->browse(function (Browser $browser) {

            $browser->visit('/profile')
                ->type('email', "invalid_email$%@example.com")
                ->click('#save')
                ->assertPathIs('/profile')
                ->assertVisible('#error');
        });

        $user = User::latest()->first();

        $this->assertNotNull($user->email_verified_at);
        $this->assertNotEquals('invalid_email$%@example.com', $user->email);

    }

    public function testInvalidEmailWithConsecutiveSpecialCharacters() : void
    {

        $this->browse(function (Browser $browser) {

            $browser->visit('/profile')
                ->type('email', "invalid_email@.@example.com")
                ->click('#save')
                ->assertPathIs('/profile');
        });

        $user = User::latest()->first();

        $this->assertNotNull($user->email_verified_at);
        $this->assertNotEquals('invalid_email@.@example.com', $user->email);

    }

    public function testInvalidEmailWithConsecutiveSpecialCharacters2() : void
    {

        $this->browse(function (Browser $browser) {

            $browser->visit('/profile')
                ->type('email', "invalid__email@example.com")
                ->click('#save')
                ->assertPathIs('/profile')
                ->assertVisible('#error');
        });

        $user = User::latest()->first();

        $this->assertNotNull($user->email_verified_at);
        $this->assertNotEquals('invalid__email@example.com', $user->email);

    }

    public function testInvalidShortEmail() : void
    {

        $this->browse(function (Browser $browser) {

            $browser->visit('/profile')
                ->type('email', "abc@example.com")
                ->click('#save')
                ->assertPathIs('/profile')
                ->assertVisible('#error');
        });

        $user = User::latest()->first();

        $this->assertNotNull($user->email_verified_at);
        $this->assertNotEquals('abc@example.com', $user->email);

    }

    public function testStudentCanEditEmailCorrectIfDontPresentOnOtherAccount() : void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/profile')
                ->type('email', "Prova@example.com")
                ->click('#save')
                ->assertVisible('#error')
                ->assertPathIs('/profile');
        });

        $user = User::latest()->first();

        $this->assertNotEquals('Prova@example.com', $user->email);
    }

    public function testStudentCanEditEmailCorrect() : void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/profile')
                ->type('email', "NuovaEmail1@gmail.com")
                ->click('#save')
                ->assertPathIs('/profile');
        });

        $user = User::latest()->first();

        $this->assertNull($user->email_verified_at);

        $user->markEmailAsVerified();
        $user->save();
        
        $this->assertEquals('NuovaEmail1@gmail.com', $user->email);
    }

    public function testStudentCanEditNameEmailCorrect() : void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/profile')
                ->type('name', "NuovoNome")
                ->type('email', "NuovaEmail2@gmail.com")
                ->click('#save')
                ->assertPathIs('/profile');
        });

        $user = User::latest()->first();

        $this->assertNull($user->email_verified_at);

        $user->markEmailAsVerified();
        $user->save();
        
        $this->assertEquals('NuovoNome', $user->name);
        $this->assertEquals('NuovaEmail2@gmail.com', $user->email);
    }


    public function testStudentCannotEditPasswordWithWrongPassword() : void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/profile')
                ->type('current_password', "Password125")  //La password corretta è Password123
                ->type('password', "NuovaPassword1")
                ->type('password_confirmation', "NuovaPassword1")
                ->click('#update')
                ->assertPathIs('/profile')
                ->assertVisible('#error');
        });

        $user = User::latest()->first();

        $this->assertNotTrue(Hash::check('NuovaPassword1', $user->password));
    }

    public function testStudentCannotEditPasswordWithDifferentPassword() : void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/profile')
                ->type('current_password', "Password123")  //La password corretta è Password123
                ->type('password', "NuovaPassword1")
                ->type('password_confirmation', "NuovaPassword2")
                ->click('#update')
                ->assertPathIs('/profile')
                ->assertVisible('#errorcurr');
        });

        $user = User::latest()->first();

        $this->assertNotTrue(Hash::check('NuovaPassword1', $user->password));
        $this->assertNotTrue(Hash::check('NuovaPassword2', $user->password));
    }

    public function testStudentCannotEditPasswordWithPasswordNotValid() : void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/profile')
                ->type('current_password', "Password123")  //La password corretta è Password123
                ->type('password', "NuovaPassword")
                ->type('password_confirmation', "NuovaPassword")
                ->click('#update')
                ->assertPathIs('/profile')
                ->assertVisible('#errorcurr');
        });

        $user = User::latest()->first();

        $this->assertNotTrue(Hash::check('NuovaPassword', $user->password));
    }

    public function testStudentCanEditPasswordWithPasswordValid() : void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/profile')
                ->type('current_password', "Password123")  //La password corretta è Password123
                ->type('password', "NuovaPassword1")
                ->type('password_confirmation', "NuovaPassword1")
                ->click('#update')
                ->assertPathIs('/profile');
        });

        $user = User::latest()->first();

        $this->assertTrue(Hash::check('NuovaPassword1', $user->password));
    }

    public function testStudentCannotDeleteProfileWithPasswordNotCorrect() : void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/profile')
                ->click('#delete')
                ->type('password', 'NuovaPassword2')
                ->click('#confirmdelete')
                ->assertVisible('#error')
                ->assertPathIs('/profile');
        });

        $latestDeletedUser = User::onlyTrashed()->latest()->first();

        $this->assertNull($latestDeletedUser); 

    }
    
    public function testStudentCanDeleteProfileWithPasswordCorrect() : void
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/profile')
                ->waitFor('#delete')
                ->click('#delete')
                ->screenshot('studio0')
                ->waitFor('#password')
                ->type('password', 'NuovaPassword1')
                ->screenshot('studio1')
                ->click('#confirmdelete')
                ->screenshot('studio2')
                ->assertPathIs('/');
        });

        $user = User::latest()->first();

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }
    */

    //Considererare i casi in cui vengono inseriti valori non validi e quindi il programma dovrebbe bloccarlo.
    
    //Partecipa ad un esame Questi dipendono troppo dalle scelte di design per adesso non li implementerò

    //Partecipa ad una esercitazione

    //Visualizza la consegna
}
