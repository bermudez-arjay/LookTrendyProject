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
        Schema::create('sales', function (Blueprint $table) {
       $table->increments('Sale_ID');
            $table->integer('Client_ID')->index('sales_ibfk_1_idx');
            $table->date('Sale_Date');
            $table->decimal('Sale_VAT', 10);
            $table->decimal('Total_Amount', 10);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
