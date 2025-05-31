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
        Schema::create('payments', function (Blueprint $table) {
            $table->integer('Payment_ID', true);
            $table->integer('Credit_ID')->nullable()->index('credit_id');
            $table->date('Payment_Date')->nullable();
            $table->decimal('Payment_Amount', 10)->nullable();
            $table->integer('Payment_Type_ID')->nullable()->index('payments_ibfk_2_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
