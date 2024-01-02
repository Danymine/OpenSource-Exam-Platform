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
        Schema::create('waiting_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('practice_id')->constrained();
        });

        Schema::table('practices', function (Blueprint $table){

            $table->boolean('allowed');
        }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waiting_rooms');
        Schema::table('practices', function (Blueprint $table) {
            $table->dropColumn('allowed');
        });
    }
};
