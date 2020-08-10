<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('main');
            $table->integer('sub1');
            $table->integer('sub2');
            $table->integer('show_in_cat');
            $table->string('type');
            $table->string('place');
            $table->string('width');
            $table->string('height');
            $table->integer('small');
            $table->string('url');
            $table->integer('dir');
            $table->longtext('code');
            $table->integer('start_to');
            $table->integer('end_to');
            $table->integer('add_by');
            $table->integer('add_date');
            $table->integer('active');
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
        Schema::drop('banners');
    }
}
