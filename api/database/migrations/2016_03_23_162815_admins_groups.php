<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdminsGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins_groups',function($table){
            $table->increments('id');
            $table->string('name');
            $table->integer('admins');
            $table->integer('settings');
            $table->integer('contactus');
            $table->integer('pages');
            $table->integer('full_admin');
            $table->integer('admins_account');
            $table->integer('news');
            $table->integer('menulink');
            $table->integer('slide');
            $table->integer('controlcitys');
            $table->integer('controlbanners');
            $table->integer('city_id');
            $table->integer('user_gender');
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
        Schema::drop('admins_groups');
    }
}
