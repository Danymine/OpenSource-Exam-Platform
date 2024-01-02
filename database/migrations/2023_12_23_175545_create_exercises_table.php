<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('question');
            $table->integer('score');
            $table->string('difficulty');
            $table->string('subject');
            $table->string('type');
            $table->string('correct_option')->nullable();
            $table->string('option_1')->nullable();
            $table->string('option_2')->nullable();
            $table->string('option_3')->nullable();
            $table->string('option_4')->nullable();
            $table->string('explanation')->nullable();
            


        });
    }
    // 'name', 'question', 'score', 'difficulty', 'subject', 'type', 'correct_option'

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
    

};
