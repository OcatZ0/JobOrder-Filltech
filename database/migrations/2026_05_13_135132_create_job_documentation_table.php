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
        Schema::create('job_documentation', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('job_assignment_id')->index('fk_job_assignment_job_documentation');
            $table->string('picture_filename');
            $table->string('picture_description');
            $table->boolean('is_delete');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_documentation');
    }
};
