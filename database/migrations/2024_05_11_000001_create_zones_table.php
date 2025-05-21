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
            $table->foreignUuid('layout_id')->nullable()->constrained('layouts')->nullOnDelete();
            $table->string('name');
            $table->string('type')->default('content'); // content, widget, etc.
            $table->integer('x');
            $table->integer('y');
            $table->integer('width');
            $table->integer('height');
            $table->integer('order')->default(0);
            $table->string('content_type')->default('html'); // html, image, video, etc.
            $table->json('style_data')->nullable();
            $table->json('settings')->nullable(); // Additional zone-specific settings
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('tenant_id');
            $table->index('layout_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zones');
    }
};
