<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('id');
            $table->unsignedInteger('sender_id');
            $table->timestamp('send_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->enum('message_type', ['text', 'voice', 'image', 'video'])->nullable();
            $table->enum('status', ['delivered', 'seen', 'send'])->nullable();
            $table->string('thumb_url', 255)->nullable();
            $table->integer('messagetable_id')->nullable();
            $table->string('messagetable_type')->nullable();
            $table->boolean('is_read')->default(0);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->foreign('sender_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
