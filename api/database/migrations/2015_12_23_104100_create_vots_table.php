<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vots', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('a1');
            $table->integer('c1');
            $table->string('a2');
            $table->integer('c2');
            $table->string('a3');
            $table->integer('c3');
            $table->string('a4');
            $table->integer('c4');
            $table->string('a5');
            $table->integer('c5');
            $table->string('a6');
            $table->integer('c6');
            $table->string('a7');
            $table->integer('c7');
            $table->string('a8');
            $table->integer('c8');
            $table->string('a9');
            $table->integer('c9');
            $table->string('a10');
            $table->integer('c10');
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
        Schema::drop('vots');
    }
}
