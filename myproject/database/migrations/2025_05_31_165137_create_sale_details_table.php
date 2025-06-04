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
        Schema::create('sale_details', function (Blueprint $table) {
            $table->integer('Sale_Detail_ID', true);
            $table->integer('Sale_ID')->nullable()->index('sale_id');
            $table->integer('Product_ID')->nullable()->index('product_id');
            $table->integer('Quantity')->nullable();
            $table->decimal('Subtotal', 10)->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Sale_Details');
    }
};
