<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->longtext('content');
            $table->string('lat');
            $table->string('lng');
            $table->string('zoom');
            $table->integer('views');
            $table->integer('add_by');
            $table->string('service');
            $table->string('servdep');
            $table->string('type_post');
            $table->string('price');
            $table->string('rent_time');
            $table->string('country');
            $table->string('city');
            $table->string('area');
            $table->string('active');
            $table->string('photo');
            $table->integer('super_post');
            $table->integer('pin_post');
            $table->longtext('comment');
            $table->string('name');
            $table->string('mobile');
            $table->string('address');
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
        Schema::drop('posts');
    }
}
