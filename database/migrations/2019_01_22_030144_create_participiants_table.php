<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipiantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->unsignedInteger('conversation_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('add_by');
            $table->enum('role', ['admin', 'user']);
            $table->timestamps();
            $table->primary(['conversation_id', 'user_id']);
            $table->foreign('conversation_id')->references('id')->on('conversations');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participants');
    }
}
