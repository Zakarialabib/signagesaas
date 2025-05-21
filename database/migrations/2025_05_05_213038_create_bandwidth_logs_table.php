<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        // Bandwidth logs
        Schema::create('bandwidth_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenant_id');
            $table->uuid('device_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->string('resource_type'); // 'content', 'media', 'api', etc.
            $table->uuid('resource_id')->nullable();
            $table->integer('bytes_transferred');
            $table->string('direction'); // 'upload', 'download'
            $table->string('status'); // 'completed', 'failed', etc.
            $table->json('metadata')->nullable();
            $table->timestamp('recorded_at');
            $table->timestamps();

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('bandwidth_logs');
    }
};
