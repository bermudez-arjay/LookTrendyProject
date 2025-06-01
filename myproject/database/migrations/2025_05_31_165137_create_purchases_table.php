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
        Schema::create('purchases', function (Blueprint $table) {
            $table->integer('Purchase_ID', true);
            $table->integer('Supplier_ID')->nullable()->index('supplier_id');
            $table->integer('User_ID')->nullable()->index('user_id');
            $table->integer('Time_ID')->nullable()->index('time_id');
            $table->decimal('Total_Amount', 10)->nullable();
            $table->string('Purchase_Status', 50)->nullable();
            $table->integer('Payment_Type_ID')->nullable()->index('payment_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
