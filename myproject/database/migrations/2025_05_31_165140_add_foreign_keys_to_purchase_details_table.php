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
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->foreign(['Purchase_ID'], 'purchase_detail_ibfk_1')->references(['Purchase_ID'])->on('purchases')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['Product_ID'], 'purchase_detail_ibfk_2')->references(['Product_ID'])->on('products')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_details', function (Blueprint $table) {
            $table->dropForeign('purchase_detail_ibfk_1');
            $table->dropForeign('purchase_detail_ibfk_2');
        });
    }
};
