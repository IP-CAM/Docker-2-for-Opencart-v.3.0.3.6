<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMsgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('msgs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid');
            $table->integer('to_user');
            $table->string('title');
            $table->longtext('content');
            $table->integer('seen');
            $table->integer('user_delete');
            $table->integer('uid_delete');
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
        Schema::drop('megs');
    }
}
