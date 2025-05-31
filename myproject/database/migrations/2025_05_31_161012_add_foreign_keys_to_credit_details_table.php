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
        Schema::table('credit_details', function (Blueprint $table) {
            $table->foreign(['Credit_ID'], 'credit_detail_ibfk_1')->references(['Credit_ID'])->on('credits')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['Product_ID'], 'credit_detail_ibfk_2')->references(['Product_ID'])->on('products')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_details', function (Blueprint $table) {
            $table->dropForeign('credit_detail_ibfk_1');
            $table->dropForeign('credit_detail_ibfk_2');
        });
    }
};
