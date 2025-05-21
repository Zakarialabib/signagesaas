<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        // Device usage logs (heartbeats, sessions, etc.)
        Schema::create('device_usage_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenant_id');
            $table->uuid('device_id');
            $table->string('event_type'); // 'heartbeat', 'boot', 'shutdown', etc.
            $table->json('metadata')->nullable();
            $table->timestamp('duration_seconds')->nullable();
            $table->timestamp('recorded_at');
            $table->timestamps();

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreign('device_id')
                ->references('id')
                ->on('devices')
                ->onDelete('cascade');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('device_usage_logs');
    }
};
