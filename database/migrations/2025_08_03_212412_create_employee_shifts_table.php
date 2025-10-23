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
        Schema::create('employee_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                ->constrained('employees')
                ->onDelete('cascade');
            $table->enum('day', [
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
                'sunday'
            ]);
            $table->enum('shift_type', [
                'morning',
                'evening',
                'split',
                'middle'
            ])->nullable();
            // sesi kertas pertama
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            // sesi kertas kedua
            $table->time('start_time_2')->nullable();
            $table->time('end_time_2')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_shifts');
    }
};
