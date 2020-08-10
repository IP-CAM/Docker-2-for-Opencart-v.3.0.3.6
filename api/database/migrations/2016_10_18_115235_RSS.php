<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RSS extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rss', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('posts');
            $table->integer('posts_num');
            $table->integer('posts_photo');
            $table->integer('users');
            $table->integer('users_num');
            $table->integer('store');
            $table->integer('store_num');
            $table->integer('pages');
            $table->integer('pages_num');
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
        Schema::drop('rss');
    }
}
