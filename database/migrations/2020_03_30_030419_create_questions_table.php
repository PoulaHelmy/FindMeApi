<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{

    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->string('name');
            $table->timestamps();
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');

        });
    }


    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
