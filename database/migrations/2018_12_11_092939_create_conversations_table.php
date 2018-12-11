<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('room_title')->nullable();
            $table->string('room_type')->nullable();
            $table->string('status')->nullable();
            $table->unsignedInteger('user_id_one')->nullable();
            $table->unsignedInteger('user_id_two')->nullable();

            $table->timestamps();
            $table->foreign('user_id_one')->references('id')->on('users')->onDelete('set null');
            $table->foreign('user_id_two')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
    }
}
