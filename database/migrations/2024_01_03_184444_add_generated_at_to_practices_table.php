<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGeneratedAtToPracticesTable extends Migration
{
    public function up()
    {
        Schema::table('practices', function (Blueprint $table) {
            $table->timestamp('generated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('practices', function (Blueprint $table) {
            $table->dropColumn('generated_at');
        });
    }
}
