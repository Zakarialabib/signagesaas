<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenant_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category');
            $table->string('status')->default('draft');
            $table->json('layout')->nullable();
            $table->json('styles')->nullable();
            $table->integer('default_duration')->default(10);
            $table->json('metadata')->nullable();
            $table->json('settings')->nullable();
            $table->string('preview_image')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
