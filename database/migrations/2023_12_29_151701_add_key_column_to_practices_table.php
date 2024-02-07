<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeyColumnToPracticesTable extends Migration
{
    public function up()
    {
        Schema::table('practices', function (Blueprint $table) {
            $table->string('key')->unique()->nullable()->after('total_score');
            $table->foreignId('user_id')->constrained();
        });
    }

    public function down()
    {
        Schema::table('practices', function (Blueprint $table) {
            $table->dropColumn('key'); 
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

        });
    }
}
