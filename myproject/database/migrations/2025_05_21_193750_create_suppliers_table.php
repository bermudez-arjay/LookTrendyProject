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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->integer('Supplier_ID', true);
            $table->string('Supplier_Identity', 17)->nullable();
            $table->string('Supplier_Name')->nullable();
            $table->string('Supplier_Address')->nullable();
            $table->string('Supplier_Phone', 20)->nullable();
            $table->string('Supplier_Email')->nullable();
            $table->string('Supplier_RUC', 17)->nullable();
            $table->boolean('Removed')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
