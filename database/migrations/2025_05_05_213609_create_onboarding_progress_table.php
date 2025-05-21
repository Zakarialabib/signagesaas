<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        // Tenant onboarding progress
        Schema::create('onboarding_progress', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenant_id');
            $table->boolean('profile_completed')->default(false);
            $table->boolean('first_device_registered')->default(false);
            $table->boolean('first_content_uploaded')->default(false);
            $table->boolean('first_screen_created')->default(false);
            $table->boolean('first_schedule_created')->default(false);
            $table->boolean('first_user_invited')->default(false);
            $table->boolean('subscription_setup')->default(false);
            $table->boolean('viewed_analytics')->default(false);
            $table->json('custom_steps')->nullable();
            $table->timestamp('completed_at')->nullable();
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
        Schema::dropIfExists('onboarding_progress');
    }
};
