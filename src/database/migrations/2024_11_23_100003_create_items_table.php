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
            $table->foreignId('category_id')->constrained('categories', 'category_id')->cascadeOnDelete();
            $table->string('condition');
            $table->string('item_image');
            $table->timestamp('created_at')->useCurrent()->nullable();
        });

        // 外部キー制約を削除
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['seller_user_id']); // ここで 'seller_user_id' に関連する外部キーを削除
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

        // 必要に応じて外部キーを再追加する
        Schema::table('items', function (Blueprint $table) {
            $table->foreign('seller_user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }
}
