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
            $table->integer('Product_ID', true);
            $table->string('Product_Name')->nullable();
            $table->string('Description', 100)->nullable();
            $table->string('Category', 100)->nullable();
            $table->decimal('Unit_Price', 10)->nullable();
            $table->boolean('Removed')->nullable();
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
