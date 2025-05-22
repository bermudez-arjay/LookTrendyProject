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
        Schema::create('users', function (Blueprint $table) {
            $table->integer('User_ID', true);
            $table->string('User_FirstName')->nullable();
            $table->string('User_LastName')->nullable();
            $table->string('User_Address')->nullable();
            $table->string('User_Phone', 20)->nullable();
            $table->string('User_Email')->nullable();
            $table->string('Password', 100)->nullable();
            $table->rememberToken();
            $table->string('User_Role', 45)->nullable();
            $table->boolean('Removed')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
