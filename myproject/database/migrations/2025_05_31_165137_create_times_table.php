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
        Schema::create('times', function (Blueprint $table) {
            $table->integer('Time_ID', true);
            $table->date('Date')->nullable();
            $table->integer('Year')->nullable();
            $table->integer('Quarter')->nullable();
            $table->integer('Month')->nullable();
            $table->integer('Week')->nullable();
            $table->time('Hour')->nullable();
            $table->integer('Day_of_Week')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('times');
    }
};
