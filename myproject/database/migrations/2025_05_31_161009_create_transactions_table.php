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
        Schema::create('transactions', function (Blueprint $table) {
            $table->integer('Transaction_ID', true);
            $table->integer('Supplier_ID')->nullable()->index('supplier_id');
            $table->integer('User_ID')->nullable()->index('user_id');
            $table->integer('Time_ID')->nullable()->index('time_id');
            $table->integer('Credit_ID')->nullable()->index('credit_id');
            $table->decimal('Total', 10)->nullable();
            $table->string('Transaction_Type', 50)->nullable();
            $table->integer('Purchase_ID')->nullable()->index('purchase_id');
            $table->integer('Payment_Type_ID')->nullable()->index('transactions_ibfk_7_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
