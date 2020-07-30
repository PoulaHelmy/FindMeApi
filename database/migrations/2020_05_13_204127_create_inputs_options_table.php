<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInputsOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inputs_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('input_id');
            $table->string('optionName');
            $table->timestamps();
            $table->foreign('input_id')->references('id')->on('inputs')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inputs_options');
    }
}
