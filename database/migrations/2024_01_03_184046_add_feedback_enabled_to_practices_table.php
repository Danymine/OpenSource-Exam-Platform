<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeedbackEnabledToPracticesTable extends Migration
{
    public function up()
    {
        Schema::table('practices', function (Blueprint $table) {
            $table->boolean('feedback_enabled')->default(false);
        });
    }

    public function down()
    {
        Schema::table('practices', function (Blueprint $table) {
            $table->dropColumn('feedback_enabled');
        });
    }
}
