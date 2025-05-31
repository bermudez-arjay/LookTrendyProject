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
        Schema::table('purchases', function (Blueprint $table) {
            $table->foreign(['Payment_Type_ID'], 'purchases_ibfk_1')->references(['Payment_Type_ID'])->on('payment_types')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['Supplier_ID'], 'purchases_ibfk_2')->references(['Supplier_ID'])->on('suppliers')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['User_ID'], 'purchases_ibfk_3')->references(['User_ID'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['Time_ID'], 'purchases_ibfk_4')->references(['Time_ID'])->on('times')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign('purchases_ibfk_1');
            $table->dropForeign('purchases_ibfk_2');
            $table->dropForeign('purchases_ibfk_3');
            $table->dropForeign('purchases_ibfk_4');
        });
    }
};
