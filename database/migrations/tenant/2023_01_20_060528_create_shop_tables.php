<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('shop_products', static function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();

            $table->string('title');
            $table->mediumText('description')->nullable();
            $table->decimal('price', 64, 0);
            $table->boolean('visibility')->default(false);
            $table->jsonb('data')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('shop_orders', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->jsonb('data')->nullable();
            $table->timestamps();
        });

        Schema::create('shop_order_items', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->constrained('shop_orders')
                ->cascadeOnDelete();
            $table->foreignId('product_id')->index();

            $table->integer('amount');
            $table->decimal('price', 64, 0)->nullable();
            $table->jsonb('data')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_order_items');
        Schema::dropIfExists('shop_orders');
        Schema::dropIfExists('shop_products');
    }
};
