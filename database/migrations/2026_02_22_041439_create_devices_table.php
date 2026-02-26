<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->uuid('device_uuid');
            $table->string('fingerprint_hash');
            $table->string('user_agent')->nullable();

            $table->boolean('trusted')->default(false);

            $table->timestamp('last_used_at')->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            
            $table->timestamps();

            $table->unique(['user_id', 'device_uuid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};