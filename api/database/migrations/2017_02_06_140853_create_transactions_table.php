<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('admin_id');
            $table->integer('post_id');
            $table->integer('timeout');
            $table->enum('type_transaction',['account','super_post']);
            $table->enum('type_timeout',['day','month','year']);
            $table->string('photo_transaction');
            $table->longtext('comments_transaction');
            $table->enum('status',['panding','refused','success']);
            $table->longtext('refused_reason');
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
        Schema::drop('transactions');
    }
}
