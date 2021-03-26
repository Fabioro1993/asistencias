<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrosCabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registros_cab', function (Blueprint $table) {
            $table->bigIncrements('id_registro');
            $table->date('fecha');
            $table->foreignId('id')->references('id')->on('users');
            $table->string('observacion')->nullable();
            $table->foreignId('id_estado')->references('id_estado')->on('estados');            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registros_cab');
    }
}
