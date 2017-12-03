<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsObjectSabDirCursoTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('newsobject_sabdircurso', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('newsobject_id')->signed();
      $table->integer('sabdircurso_id')->signed();
      $table->nullableTimestamps();
      // $table->foreign('newsobject_id')->references('id')->on('newsobjects');
      // $table->foreign('sabdircurso_id')->references('id')->on('sabdircursos');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()  {
      //
  }
}
