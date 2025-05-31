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
        Schema::create('inventories', function (Blueprint $table) {
            $table->integer('Inventory_ID', true);
            $table->integer('Product_ID')->nullable()->index('product_id');
            $table->integer('Current_Stock')->nullable();
            $table->integer('Minimum_Stock')->nullable();
            $table->date('Last_Update')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
