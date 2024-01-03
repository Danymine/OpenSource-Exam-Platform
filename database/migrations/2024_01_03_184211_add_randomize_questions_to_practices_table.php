<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRandomizeQuestionsToPracticesTable extends Migration
{
    public function up()
    {
        Schema::table('practices', function (Blueprint $table) {
            $table->boolean('randomize_questions')->default(false);
        });
    }

    public function down()
    {
        Schema::table('practices', function (Blueprint $table) {
            $table->dropColumn('randomize_questions');
        });
    }
}
