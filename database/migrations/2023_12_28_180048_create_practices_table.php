<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePracticesTable extends Migration
{
    public function up()
    {
        // Crea la tabella 'practices' se non esiste già
        if (!Schema::hasTable('practices')) {
            Schema::create('practices', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->string('title');
                $table->string('description');
                $table->string('difficulty');
                $table->string('subject');
                $table->integer('total_score');
                $table->integer('time');
            });
        }
    }

    public function down()
    {
        // Rimuovi la tabella 'practices' se esiste
        Schema::dropIfExists('practices');
    }
}
