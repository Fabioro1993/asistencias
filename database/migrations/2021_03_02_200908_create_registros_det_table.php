<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrosDetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registros_det', function (Blueprint $table) {
            $table->bigIncrements('id_reg_det');
            $table->foreignId('id_registro')->references('id_registro')->on('registros_cab');
            $table->integer('cedula');
            $table->string('nombre');
            $table->string('comentario')->nullable();
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
        Schema::dropIfExists('registros_det');
    }
}
