<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class Autenticazione extends DuskTestCase
{
   /* Test per verificare i controlli relativi alla registrazione e all'accesso */

   public function testUsersCanSeeRegister() : void
   {
       $this->browse(function (Browser $browser) {
           $browser->visit('/')
                   ->clickLink('Register')
                   ->assertPathIs('/register');
       });
   }

   public function testUsersCanSeeLogin() : void
   {
       $this->browse(function (Browser $browser) {
           $browser->visit('/')
                   ->clickLink('Log in')
                   ->assertPathIs('/login');
       });
   }

   public function testUsersCannotRegisterStudentWithoutDate() : void
   {
       $this->browse(function (Browser $browser){

           $browser->visit('/register')
                   ->click('button[type="submit"]')
                   ->assertPathIsNot('/verify-email')
                   ->assertPathIs('/register');
       });
   }

   public function testUsersCannotRegisterStudentWithoutName() : void
   {
       $this->browse(function (Browser $browser) {
           $browser->visit('/register')
                   ->type('email', 'test@example.com')
                   ->type('password', 'Password1')
                   ->type('password_confirmation', 'Password1')
                   ->radio('role', 'Student')
                   ->click('button[type="submit"]')
                   ->assertPathIs('/register');
       });
   }

   public function testUsersCannotRegisterStudentWithoutEmail() : void
   {
       $this->browse(function (Browser $browser) {
           $browser->visit('/register')
                   ->type('name', 'Test User')
                   ->type('password', 'Password1')
                   ->type('password_confirmation', 'Password1')
                   ->radio('role', 'Student')
                   ->click('button[type="submit"]')
                   ->assertPathIs('/register');
       });
   }

   public function testUsersCannotRegisterStudentWithoutEmailValid() : void
   {
       $this->browse(function (Browser $browser) {
           $browser->visit('/register')
                   ->type('name', 'Test User')
                   ->type('email', 'prova.example')
                   ->type('password', 'Password1')
                   ->type('password_confirmation', 'Password1')
                   ->radio('role', 'Student')
                   ->click('button[type="submit"]')
                   ->assertPathIs('/register');
       });
   }

   public function testUsersCannotRegisterStudentWithoutPassword() : void
   {
       $this->browse(function (Browser $browser) {
           $browser->visit('/register')
                   ->type('name', 'Test User')
                   ->type('email', 'test@example.com')
                   ->type('password_confirmation', 'Password1')
                   ->radio('role', 'Student')
                   ->click('button[type="submit"]')
                   ->assertPathIs('/register');
       });
   }

   public function testUsersCannotRegisterStudentWithoutPasswordConfirmation() : void
   {
       $this->browse(function (Browser $browser) {
           $browser->visit('/register')
                   ->type('name', 'Test User')
                   ->type('email', 'test@example.com')
                   ->type('password', 'Password1')
                   ->radio('role', 'Student')
                   ->click('button[type="submit"]')
                   ->assertPathIs('/register');
       });
   }

   public function testUsersCannotRegisterStudentWithoutPasswordValid() : void
   {
       $this->browse(function (Browser $browser) {
           $browser->visit('/register')
                   ->type('name', 'Test User')
                   ->type('email', 'test@example.com')
                   ->type('password', 'password')
                   ->type('password_confirmation', 'password')  //Questa rispetta la dimensione minima
                   ->radio('role', 'Student')
                   ->click('button[type="submit"]')
                   ->assertPathIs('/register');
       });
   }

   public function testUsersCannotRegisterStudentWithoutPasswordValid2() : void
   {
       $this->browse(function (Browser $browser) {
           $browser->visit('/register')
                   ->type('name', 'Test User')
                   ->type('email', 'test@example.com')
                   ->type('password', 'password')
                   ->type('password_confirmation', 'Password')  //Questa ha solo la lettera maiuscola ma non il numero e rispetta la dimensione minima
                   ->radio('role', 'Student')
                   ->click('button[type="submit"]')
                   ->assertPathIs('/register');
       });
   }

   public function testUsersCannotRegisterStudentWithoutPasswordValid3() : void
   {
       $this->browse(function (Browser $browser) {
           $browser->visit('/register')
                   ->type('name', 'Test User')
                   ->type('email', 'test@example.com')
                   ->type('password', 'password')
                   ->type('password_confirmation', 'Pass2')     //Questa ha entrambi i caratteri richiesti ma non rispetta la dimensione minima
                   ->radio('role', 'Student')
                   ->click('button[type="submit"]')
                   ->assertPathIs('/register');
       });
   }

   public function testUsersCannotRegisterStudentWithoutRole() : void
   {
       $this->browse(function (Browser $browser) {
           $browser->visit('/register')
                   ->type('name', 'Test User')
                   ->type('email', 'test@example.com')
                   ->type('password', 'Password1')
                   ->type('password_confirmation', 'Password1')
                   ->click('button[type="submit"]')
                   ->assertPathIs('/register');
       });
   }

   public function testUsersCannnotLoginErrorWithoutData() : void 
   {

       $this->browse(function (Browser $browser){

           $browser->visit('/login')
               ->click('button[type="submit"]')
               ->assertPathIs('/login');
       });
   }

   public function testUsersCannnotLoginErrorWithoutEmail() : void 
   {

       $this->browse(function (Browser $browser){

           $browser->visit('/login')
               ->type('password', 'Password1')
               ->click('button[type="submit"]')
               ->assertPathIs('/login');
       });
   }

   public function testUsersCannnotLoginErrorWithoutPassword() : void 
   {

       $this->browse(function (Browser $browser){

           $browser->visit('/login')
               ->type('email', 'test@example.com')
               ->click('button[type="submit"]')
               ->assertPathIs('/login');
       });
   }

   public function testUsersCannnotLoginErrorWithoutAccount() : void 
   {

       $this->browse(function (Browser $browser){

           $browser->visit('/login')
               ->type('email', '1234@example.com')
               ->type('password', 'Password123')
               ->click('button[type="submit"]')
               ->assertPathIs('/login');
       });
   }

}
