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
            });
        }

        // Aggiungi la colonna 'practice_id' alla tabella 'exercises'
        Schema::table('exercises', function (Blueprint $table) {
            $table->unsignedBigInteger('practice_id')->nullable();
            $table->foreign('practice_id')->references('id')->on('practices');
        });
    }

    public function down()
    {
        // Rimuovi la colonna 'practice_id' dalla tabella 'exercises'
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropForeign(['practice_id']);
            $table->dropColumn('practice_id');
        });

        // Rimuovi la tabella 'practices' se esiste
        Schema::dropIfExists('practices');
    }
}
