<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('promotions', function (Blueprint $table) {
        $table->id(); // hoặc $table->uuid('id')->primary();
        $table->string('code')->unique();
        $table->text('description')->nullable();
        $table->enum('discount_type', ['percentage', 'fixed']);
        $table->decimal('discount_value', 8, 2);
        $table->dateTime('start_date');
        $table->dateTime('end_date');
        $table->integer('usage_limit')->nullable();
        $table->integer('used_count')->default(0);
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
