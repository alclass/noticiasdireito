<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsObjectTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('newsobjects', function (Blueprint $table) {
      $table->increments('id');
      $table->date('newsdate')->unique();
      $table->string('underlined_newstitle', 170)->nullable();
      $table->string('newstitle', 255)->nullable();
      $table->string('subtitle', 255)->nullable();
      $table->text('description')->nullable();
      $table->smallInteger('main_knowledgearea_id')->unsigned()->nullable();
      $table->nullableTimestamps();
      //$table->foreign('sabdircurso_id')->references('id')->on('sabdircursos');
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
