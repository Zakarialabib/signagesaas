<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('content_zone', function (Blueprint $table) {
            $table->uuid('content_id');
            $table->uuid('zone_id');
            $table->integer('order')->default(0);
            $table->integer('duration')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->primary(['content_id', 'zone_id']);
            $table->foreign('content_id')->references('id')->on('contents')->onDelete('cascade');
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_zone');
    }
};
