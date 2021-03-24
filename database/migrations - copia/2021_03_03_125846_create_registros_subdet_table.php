<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrosSubdetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registros_subdet', function (Blueprint $table) {
            $table->bigIncrements('id_reg_sub');
            $table->foreignId('id_registro')->references('id_registro')->on('registros_cab');
            $table->foreignId('id_reg_det')->references('id_reg_det')->on('registros_det');
            $table->foreignId('id_evaluacion')->references('id_evaluacion')->on('evaluaciones');
            $table->string('resultado')->default(0);
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
        Schema::dropIfExists('registros_subdet');
    }
}
