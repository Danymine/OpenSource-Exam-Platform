<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AggiungiTypeAPractices extends Migration
{
    public function up()
    {
        Schema::table('practices', function (Blueprint $table) {
            $table->string('type'); 
        });
    }

    public function down()
    {
        Schema::table('practices', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
