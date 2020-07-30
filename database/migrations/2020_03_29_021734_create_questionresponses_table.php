<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionresponsesTable extends Migration
{

    public function up()
    {
        Schema::create('questionresponses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->string('question');
            $table->string('answer');
            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');

        });
    }


    public function down()
    {
        Schema::dropIfExists('questionresponses');
    }
}
