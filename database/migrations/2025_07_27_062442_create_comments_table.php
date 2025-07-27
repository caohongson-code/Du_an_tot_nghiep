<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id')->nullable(); // admin trả lời sẽ là null
            $table->text('content');
            $table->tinyInteger('rating')->nullable(); // chỉ dùng cho client đánh giá
            $table->unsignedBigInteger('parent_id')->nullable(); // trả lời
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
         $table->foreign('user_id')->references('id')->on('accounts')->onDelete('set null');
            $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
