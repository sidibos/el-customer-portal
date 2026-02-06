<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add customer_id (nullable first to avoid issues if existing users exist)
            $table->foreignId('customer_id')
                ->nullable(false)
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('type', ['primary', 'authorised'])
                ->default('authorised')
                ->after('password');

            $table->string('phone', 30)
                ->nullable()
                ->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn(['customer_id', 'type', 'phone']);
        });
    }
};