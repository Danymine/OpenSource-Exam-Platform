<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Practice;
use App\Models\Exercise;
use App\Models\AssistanceRequest;
use App\Models\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

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
                ->type('date_birth', "07/14/2001")
                ->click('#save')
                ->assertPathIs('/profile');
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
                ->type('email', "Prova@example.com")    //Esiste visto che lo creo io
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

    public function testStudentCannotDeleteProfileWithoutConfirm() : void
    {
        $this->browse(function (Browser $browser) {
    
            $browser->visit('/profile')
                ->click('#delete-account-btn')
                ->dismissDialog();
    
            $browser->assertPathIs('/profile');
        });
    
        // Verifica che l'utente non sia stato eliminato
        $latestUser = User::latest()->first();
        $this->assertNotNull($latestUser);
    }
    
    public function testStudentCanDeleteProfileWithConfirm() : void
    {
        $this->browse(function (Browser $browser) {
    
            $browser->visit('/profile')
                ->click('#delete-account-btn')
                ->acceptDialog()
                ->assertPathIs('/');
        });

        // Verifica che l'utente sia eliminato
        $latestDeletedUser = User::onlyTrashed()->latest()->first();
        $this->assertNotNull($latestDeletedUser);

        $latestDeletedUser->forceDelete();
    }

    /* 
    *
    *
    *   Test per la partecipazione dello studente ad una prova (è indipendente dal tipo)
    */
    
    public function testStudentCanJoin() : void
    {
        $practice = new Practice([
            'title' => 'Titolo della pratica',
            'description' => 'Descrizione della pratica',
            'difficulty' => 'Facile',
            'subject' => 'Materia della pratica',
            'total_score' => 100,
            'key' => 'O6N71p',
            'user_id' => 1, 
            'feedback_enabled' => 0,
            'randomize_questions' => 1,
            'allowed' => 0,
            'practice_date' => Carbon::today(),
            'type' => 'Exam',
            'public' => 0,
            'time' => 60,
        ]);
        
        $practice->save();

        $this->browse(function (Browser $browser) use ($practice) {

            $browser->loginAs(2)
                ->visit('/dashboard')
                ->type('key', $practice->key)
                ->press('#join')
                ->assertPathIs('/waiting-room/' . $practice->key);
    
            $user = User::find(2);
            $this->assertTrue($user->waitingroom()->where('practice_id', $practice->id)->exists());
        });

        $user = User::find(2);
        $user->waitingroom()->detach($practice->id);

        $practice->delete();
        $practice->forceDelete();
    }

    public function testStudentCannotJoinWithoutKeyExist() : void
    {

        $this->browse(function (Browser $browser){

            $browser->loginAs(2)
                ->visit('/dashboard')
                ->type('key', "O00000") //Questa key non esiste
                ->press('#join')
                ->assertPathIs('/dashboard');
        });
    }

    public function testStudentCannotJoinWithoutKeyFormatBad() : void
    {

        $this->browse(function (Browser $browser){

            $browser->loginAs(2)
                ->visit('/dashboard')
                ->type('key', "O000000") //Questa key ha 7 caratteri
                ->press('#join')
                ->assertPathIs('/dashboard');
        });
    }

    public function testStudentCannotJoinWithoutKeyWithNotChart() : void
    {

        $this->browse(function (Browser $browser){

            $browser->loginAs(2)
                ->visit('/dashboard')
                ->type('key', "O0!000") //Questa key ha 7 caratteri
                ->press('#join')
                ->assertPathIs('/dashboard');
        });
    }

    public function testStudentCannotJoinForFutureDate() : void
    {
        $practice = new Practice([
            'title' => 'Titolo della pratica',
            'description' => 'Descrizione della pratica',
            'difficulty' => 'Facile',
            'subject' => 'Materia della pratica',
            'total_score' => 100,
            'key' => 'O6N71p',
            'user_id' => 1, 
            'feedback_enabled' => 0,
            'randomize_questions' => 1,
            'allowed' => 0,
            'practice_date' => Carbon::now()->addDays(1),
            'type' => 'Exam',
            'public' => 0,
            'time' => 60,
        ]);
        
        $practice->save();

        $this->browse(function (Browser $browser) use ($practice) {

            $browser->loginAs(2)
                ->visit('/dashboard')
                ->type('key', $practice->key)
                ->press('#join')
                ->assertPathIs('/dashboard');
    
            $user = User::find(2);
            $this->assertFalse($user->waitingroom()->where('practice_id', $practice->id)->exists());
        });

        $practice->delete();
        $practice->forceDelete();
    }

    public function testStudentCannotJoinForPastDate() : void
    {
        $practice = new Practice([
            'title' => 'Titolo della pratica',
            'description' => 'Descrizione della pratica',
            'difficulty' => 'Facile',
            'subject' => 'Materia della pratica',
            'total_score' => 100,
            'key' => 'O6N71p',
            'user_id' => 1, 
            'feedback_enabled' => 0,
            'randomize_questions' => 1,
            'allowed' => 0,
            'practice_date' => Carbon::now()->subDays(1),
            'type' => 'Exam',
            'public' => 0,
            'time' => 60,
        ]);
        
        $practice->save();

        $this->browse(function (Browser $browser) use ($practice) {

            $browser->loginAs(2)
                ->visit('/dashboard')
                ->type('key', $practice->key)
                ->press('#join')
                ->assertPathIs('/dashboard');
    
            $user = User::find(2);
            $this->assertFalse($user->waitingroom()->where('practice_id', $practice->id)->exists());
        });

        $practice->delete();
        $practice->forceDelete();
    }

    public function testStudentCanJoinAndDoTest() : void
    {

        $practice = new Practice([
            'title' => 'Titolo della pratica',
            'description' => 'Descrizione della pratica',
            'difficulty' => 'Facile',
            'subject' => 'Materia della pratica',
            'total_score' => 100,
            'key' => 'O6N71p',
            'user_id' => 1, 
            'feedback_enabled' => 0,
            'randomize_questions' => 1,
            'allowed' => 1,
            'practice_date' => Carbon::today(),
            'type' => 'Exam',
            'public' => 0,
            'time' => 60,
        ]);
        
        $practice->save();

        $this->browse(function (Browser $browser) use ($practice) {

            $browser->loginAs(2)
                ->visit('/dashboard')
                ->type('key', $practice->key)
                ->press('#join')
                ->assertPathIs('/waiting-room/' . $practice->key);
    
            $user = User::find(2);
            //Li viene data dal docente la possibilità di partecipare
            $user->waitingroom()->where('practice_id', $practice->id)->update(['status' => 'execute']);

            $browser->pause(10000);
            $browser->assertPathIs('/view-test/' . $practice->key);
        });

        $user = User::find(2);
        $user->waitingroom()->detach($practice->id);

        $practice->delete();
        $practice->forceDelete();
    }
    /* Da continuare dopo
    public function testStudentCanJoinAndSendTest() : void
    {

        $practice = new Practice([
            'title' => 'Titolo della pratica',
            'description' => 'Descrizione della pratica',
            'difficulty' => 'Facile',
            'subject' => 'Materia della pratica',
            'total_score' => 100,
            'key' => 'O6N71p',
            'user_id' => 1, 
            'feedback_enabled' => 0,
            'randomize_questions' => 0,
            'allowed' => 1,
            'practice_date' => Carbon::today(),
            'type' => 'Exam',
            'public' => 0,
            'time' => 60,
        ]);
        
        $practice->save();

        $exercisesOpen = Exercise::where('type', 'Risposta Aperta')->take(1)->get();
        $exercisesMultiple = Exercise::where('type', 'Risposta Multipla')->take(1)->get();
        $exercisesTrueFalse = Exercise::where('type', 'Vero o Falso')->take(1)->get();
        
        $exercisesRandom = Exercise::whereNotIn('id', $exercisesOpen->pluck('id')->merge($exercisesMultiple->pluck('id'))->merge($exercisesTrueFalse->pluck('id'))->toArray())
                                    ->inRandomOrder()
                                    ->take(2)
                                    ->get();
        
        $exercises = $exercisesOpen->merge($exercisesMultiple)->merge($exercisesTrueFalse)->merge($exercisesRandom);
        $sumScore=0;
        $id_exercise = [
            'id' => [],
            'type' => [],
            'value' => [
                [],
                [],
                [],
                []
            ]
        ]; 

        foreach ($exercises as $exercise) {
            
            $practice->exercises()->attach($exercise->id);
            array_push($id_exercise['id'], $exercise->id);
            array_push($id_exercise['type'], $exercise->type);
            array_push($id_exercise['value'][0], $exercise->option_1);
            array_push($id_exercise['value'][1], $exercise->option_2);
            array_push($id_exercise['value'][2], $exercise->option_3);
            array_push($id_exercise['value'][3], $exercise->option_4);
            $sumScore += $exercise->score;
        }

        $practice->total_score = $sumScore;
        $practice->save();

        $this->browse(function (Browser $browser) use ($practice, $id_exercise) {

            $browser->loginAs(2)
                ->visit('/dashboard')
                ->type('key', $practice->key)
                ->press('#join')
                ->assertPathIs('/waiting-room/' . $practice->key);
    
            $user = User::find(2);
            //Li viene data dal docente la possibilità di partecipare
            $user->waitingroom()->where('practice_id', $practice->id)->update(['status' => 'execute']);

            $browser->pause(10000);

            for( $i = 0; $i < 5; $i++ ){

                if( $id_exercise["type"][$i] == 'Risposta Aperta' ){

                    //Comportati con la consopevolezza che siamo davanti a un esercizio di cui sappiamo il tipo e quindi anche le modalità di risposte
                    $browser->pause(1000)->type('#text_' . $id_exercise['id'][$i], "Inserisco una risposta")->screenshot('aperto' . $i)
                        ->press("#nextBtn");
                }
                else if( $id_exercise["type"][$i] == 'Risposta Multipla'  ){

                    $browser->radio('risposte[' . $id_exercise['id'][$i] . "]", $id_exercise["value"][0])->screenshot('multiplo' . $i)
                        ->press("#nextBtn");
                }
                else{

                    //Sappiamo che è un vero falso.
                }
            }
            $browser->press("#submitBtn")->waitFor('#confirmationModal')->press("#confirmSend")->assertPathIs('/dashboard');
        });

        $user = User::find(2);
        $user->waitingroom()->detach($practice->id);

        $delivery = Delivered::latest()->first();
        
        $this->assertNotNull($delivery);
        $this->assertEquals($practice->id, $delivery->practice_id);
        $this->assertEquals($delivery->user_id, 2);
        
        $answers = $delivery->answers();
        foreach( $answers as $answer ){

            for( $i = 0; $i < 5; $i++ ){

                if( $answer->exercise_id == $id_exercise['id'][$i]){

                    if( $id_exercise['type'][$i] == "Risposta Aperta"){

                        $this->assertEquals($answer->response, "Inserisco una risposta");
                    }
                    else if( $id_exercise["type"][$i] == 'Risposta Multipla'  ){

                        $this->assertEquals($answer->response, Exercise::find($id_exercise['id'][$i]->option_1));
                    }
                    else{
    
                        $this->assertEquals($answer->response, "vero");
                    }
                }
            }
        }

        $practice->exercises()->detach();

        $practice->delete();
    }
    */

    /*
    *
    *
    *   Consegne. (Visualizza consegna)
    */

     /*
    *
    *
    *   Richiesta di Assistenza.
    */

    public function testStudentCanCreateRequest() : void
    {

        $this->browse(function (Browser $browser){

            $browser->loginAs(2)
                ->visit('/dashboard')
                ->press('#requests')
                ->clickLink('Richiedi assistenza')
                ->assertPathIs('/richiedi-assistenza');
        });
    }

    public function testStudentCanSendRequest() : void
    {

        $this->browse(function (Browser $browser){

            $browser->loginAs(2)
                ->visit('/dashboard')
                ->press('#requests')
                ->clickLink('Richiedi assistenza')
                ->type("subject", "Oggetto Assistenza")
                ->type("description", "Descrizione del problema")
                ->press('#send-request')
                ->assertPathIs('/dashboard');
        });

        $request = AssistanceRequest::latest()->first();

        $this->assertEquals($request->subject, "Oggetto Assistenza");
        $this->assertEquals($request->description, "Descrizione del problema");
    }

    public function testStudentCanSeeListRequestAndSendResponse() : void
    {

        $request = AssistanceRequest::latest()->first();

        $this->browse(function (Browser $browser) use ($request) {

            $browser->loginAs(2)
                ->visit('/view-request/' . $request->id)
                ->type('response', "Rispondo ad un messaggio")
                ->press("#send-response")
                ->assertPathIs('/view-request/' . $request->id);
        });

        $responses = Response::latest()->first();

        $this->assertEquals($responses->response, "Rispondo ad un messaggio");
        $this->assertEquals($responses->assistance_request_id, $request->id);
        $this->assertEquals($responses->user_id, 2);

        $responses->delete();
        $request->delete();
    }
}
