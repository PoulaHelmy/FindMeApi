<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInputsSubcatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inputs_subcats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inputs_id')->unsigned();
            $table->unsignedBigInteger('subcats_id')->unsigned();
            $table->foreign('inputs_id')->references('id')->on('inputs')->onDelete('cascade');
            $table->foreign('subcats_id')->references('id')->on('subcats')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inputs_subcats');
    }
}
