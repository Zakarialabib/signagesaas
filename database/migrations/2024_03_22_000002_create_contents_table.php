<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('screen_id')->constrained()->cascadeOnDelete();
            $table->string('template_id');
            $table->string('tenant_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type'); // image, video, html, url
            $table->json('content_data'); // flexible content data in JSON format
            $table->string('status')->default('active'); // active, inactive, draft
            $table->integer('duration')->nullable(); // duration in seconds
            $table->integer('order')->default(0); // display order
            $table->timestamp('start_date')->nullable(); // when to start showing content
            $table->timestamp('end_date')->nullable(); // when to stop showing content
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
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
