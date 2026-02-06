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
        Schema::create('consumptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meter_id')->constrained()->cascadeOnDelete();

            // store as first day of month (e.g. 2026-02-01)
            $table->date('month');
            $table->decimal('usage', 12, 3);

            $table->timestamps();

            $table->unique(['meter_id', 'month']);
            $table->index(['month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumptions');
    }
};