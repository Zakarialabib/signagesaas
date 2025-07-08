<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type')->default('GENERAL_AREA'); // E.g., GENERAL_AREA, WALL_MOUNT, KIOSK
            $table->integer('x')->comment('Represents x-coordinate on a map/floor plan');
            $table->integer('y')->comment('Represents y-coordinate on a map/floor plan');
            $table->integer('width')->comment('Represents width on a map/floor plan or physical width');
            $table->integer('height')->comment('Represents height on a map/floor plan or physical height');
            $table->json('style_data')->nullable()->comment('Visual styling for map representation, e.g., color, icon');
            $table->json('metadata')->nullable()->comment('Other structured data, e.g., floor_number, gps_coordinates');
            $table->timestamps();
            $table->softDeletes();

            $table->index('tenant_id');
            // Removed layout_id index
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zones');
    }
};
