<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id('chat_id');
            $table->foreignId('sender_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items', 'item_id')->cascadeOnDelete();
            $table->enum('sender_role', ['buyer', 'seller']);
            $table->string('chat_message');
            $table->string('message_image')->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
}
