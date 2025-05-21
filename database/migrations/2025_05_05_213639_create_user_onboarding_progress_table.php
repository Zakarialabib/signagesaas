<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        // User onboarding progress (for multi-user setups)
        Schema::create('user_onboarding_progress', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenant_id');
            // Use unsignedBigInteger to match users.id type
            $table->unsignedBigInteger('user_id');
            $table->json('completed_steps');
            $table->timestamp('last_step_completed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->boolean('dismissed')->default(false);
            $table->timestamps();

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unique(['tenant_id', 'user_id']);
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('user_onboarding_progress');
    }
};
