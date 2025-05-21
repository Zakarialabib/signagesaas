<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('token', 64)->unique()->nullable();
            $table->string('tenant_id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('type');
            $table->string('status')->default('offline');
            $table->string('hardware_id')->unique();
            $table->json('hardware_info')->nullable();
            $table->string('activation_token', 32)->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamp('last_ping_at')->nullable();
            $table->timestamp('last_sync_at')->nullable();
            $table->string('screen_resolution')->nullable();
            $table->string('orientation')->default('landscape');
            $table->string('os_version')->nullable();
            $table->string('app_version')->nullable();
            $table->json('location')->nullable();
            $table->string('timezone')->nullable();
            $table->json('settings')->nullable();
            $table->string('registration_code')->nullable()->unique();
            $table->json('system_info')->nullable();
            $table->json('storage_info')->nullable();
            $table->string('sync_status')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
