<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribe_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('jid');
            $table->integer('uid');
            $table->integer('accept');
            $table->integer('accept_date');
            $table->integer('accept_by');
            $table->integer('uid_read');
            $table->integer('add_date');
            $table->integer('read_date');
            $table->longtext('content');
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
        Schema::drop('subscribe_jobs');
    }
}
