<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('schedule_content', function (Blueprint $table) {
            $table->foreignUuid('schedule_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('content_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('order')->default(0);
            $table->unsignedInteger('duration')->default(10); // Duration in seconds
            $table->timestamps();

            $table->primary(['schedule_id', 'content_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_content');
    }
};
