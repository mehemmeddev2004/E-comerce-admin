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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->json('name'); // Multi-language support: {az: "", en: "", ru: ""}
            $table->json('description')->nullable();
            $table->string('brand');
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->decimal('discount', 5, 2)->nullable()->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_new')->default(false);
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('image_url')->nullable();
            $table->json('images')->nullable(); // Array of image URLs
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
