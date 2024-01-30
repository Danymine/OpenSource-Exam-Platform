<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRichiestaAssistenzasTable extends Migration
{
    public function up()
    {
        Schema::create('richiesta_assistenzas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('ruolo');
            $table->text('problema');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('richiesta_assistenzas');
    }
}
