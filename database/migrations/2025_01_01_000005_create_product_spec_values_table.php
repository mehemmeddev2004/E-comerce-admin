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
        Schema::create('product_spec_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_spec_id')->constrained('product_specs')->onDelete('cascade');
            $table->string('key'); // e.g., "red", "xl"
            $table->string('value'); // Display value
            $table->timestamps();

            $table->unique(['product_spec_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_spec_values');
    }
};
