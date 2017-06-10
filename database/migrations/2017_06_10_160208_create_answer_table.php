<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('post_id')->unsigned();
          $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
          $table->index('post_id');
          $table->string('content');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('answers');
    }
}
