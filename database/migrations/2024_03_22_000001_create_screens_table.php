<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('screens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('device_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('zone_id')->nullable()->constrained('zones')->onDelete('set null'); // Added
            $table->string('template_id')->nullable();
            $table->string('tenant_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('active'); // active, inactive, maintenance
            $table->string('resolution')->nullable(); // e.g. 1920x1080
            $table->string('orientation')->default('landscape'); // landscape, portrait
            $table->json('location')->nullable(); // can store latitude, longitude, address, etc.
            $table->json('settings')->nullable(); // flexible settings in JSON format
            $table->json('metadata')->nullable(); // flexible metadata in JSON format
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreign('template_id')
                ->references('id')
                ->on('templates')
                ->onDelete('cascade');

            $table->index('zone_id'); // Added index for zone_id
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('screens');
    }
};
