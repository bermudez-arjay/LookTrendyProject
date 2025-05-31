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
        Schema::create('clients', function (Blueprint $table) {
            $table->integer('Client_ID', true);
            $table->string('Client_Identity', 17)->nullable();
            $table->string('Client_FirstName')->nullable();
            $table->string('Client_LastName')->nullable();
            $table->string('Client_Address')->nullable();
            $table->string('Client_Phone', 20)->nullable();
            $table->string('Client_Email')->nullable();
            $table->boolean('Removed')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
