<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->longtext('content');
            $table->integer('user_id');
            $table->integer('start_send');
            $table->enum('send_at',['all','subscribe','admin','user','store','custom']);
            $table->longtext('custom_list');
            $table->integer('was_sent');
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
        Schema::drop('mail_lists');
    }
}
