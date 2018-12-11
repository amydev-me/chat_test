<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversationRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversation_replies', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('send_date')->nullable();
            $table->mediumText('message')->nullable();
            $table->string('status')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('conversation_id')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversation_replies');
    }
}
