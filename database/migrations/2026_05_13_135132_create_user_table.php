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
        Schema::create('user', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 200);
            $table->string('username', 200)->unique('username');
            $table->string('password', 200);
            $table->enum('role', ['superadmin', 'admin', 'am', 'teknisi']);
            $table->boolean('is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
