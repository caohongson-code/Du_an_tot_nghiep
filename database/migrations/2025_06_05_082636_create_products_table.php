<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('category_id')->nullable(); // Để tạm nullable
        $table->string('product_name');
        $table->decimal('price', 15, 2);
        $table->decimal('discount_price', 15, 2)->nullable();
        $table->string('image')->nullable();
        $table->integer('quantity')->default(0);
        $table->integer('views')->default(0);
        $table->text('description')->nullable();
        $table->tinyInteger('status')->default(1); // 0: ẩn, 1: hiển thị
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
