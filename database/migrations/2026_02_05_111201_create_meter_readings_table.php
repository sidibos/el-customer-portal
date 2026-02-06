<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('meter_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meter_id')->constrained()->cascadeOnDelete();
            $table->decimal('reading', 12, 3);
            $table->dateTime('read_at');
            $table->timestamps();

            $table->index(['meter_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meter_readings');
    }
};