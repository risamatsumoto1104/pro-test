<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id('item_id');
            $table->foreignId('seller_user_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->string('item_name');
            $table->string('brand_name')->nullable();
            $table->decimal('price', 10, 0);
            $table->text('description');
            $table->string('condition');
            $table->string('item_image');
            $table->timestamp('created_at')->useCurrent()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
