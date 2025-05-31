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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->integer('Purchase_Detail_ID', true);
            $table->integer('Purchase_ID')->nullable()->index('purchase_id');
            $table->integer('Product_ID')->nullable()->index('product_id');
            $table->integer('Quantity')->nullable();
            $table->decimal('Unit_Price', 10)->nullable();
            $table->decimal('Subtotal', 10)->nullable();
            $table->decimal('VAT', 10)->nullable();
            $table->decimal('Total_With_VAT', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};
