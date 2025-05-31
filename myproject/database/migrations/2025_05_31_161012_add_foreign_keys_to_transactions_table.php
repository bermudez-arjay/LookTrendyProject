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
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign(['Supplier_ID'], 'transactions_ibfk_2')->references(['Supplier_ID'])->on('suppliers')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['User_ID'], 'transactions_ibfk_3')->references(['User_ID'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['Time_ID'], 'transactions_ibfk_4')->references(['Time_ID'])->on('times')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['Credit_ID'], 'transactions_ibfk_5')->references(['Credit_ID'])->on('credits')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['Purchase_ID'], 'transactions_ibfk_6')->references(['Purchase_ID'])->on('purchases')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['Payment_Type_ID'], 'transactions_ibfk_7')->references(['Payment_Type_ID'])->on('payment_types')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('transactions_ibfk_2');
            $table->dropForeign('transactions_ibfk_3');
            $table->dropForeign('transactions_ibfk_4');
            $table->dropForeign('transactions_ibfk_5');
            $table->dropForeign('transactions_ibfk_6');
            $table->dropForeign('transactions_ibfk_7');
        });
    }
};
