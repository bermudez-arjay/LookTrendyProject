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
        Schema::create('credits', function (Blueprint $table) {
            $table->integer('Credit_ID', true);
            $table->integer('Client_ID')->nullable()->index('client_id');
            $table->date('Start_Date')->nullable();
            $table->date('Due_Date')->nullable();
            $table->decimal('Total_Amount', 10)->nullable();
            $table->decimal('Interest_Rate', 5)->nullable();
            $table->string('Credit_Status', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
