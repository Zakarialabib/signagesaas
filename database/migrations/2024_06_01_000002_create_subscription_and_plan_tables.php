<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        // Plans
        Schema::create('plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price_monthly', 10, 2);
            $table->decimal('price_yearly', 10, 2);
            $table->json('features')->nullable();
            $table->integer('trial_days')->nullable();
            $table->integer('max_devices')->default(1);
            $table->integer('max_screens')->default(1);
            $table->integer('max_users')->default(1);
            $table->integer('max_storage_mb')->default(1024); // 1GB
            $table->integer('max_bandwidth_mb')->default(5120); // 5GB
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Subscriptions
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenant_id');
            $table->uuid('plan_id');
            $table->string('status'); // 'active', 'canceled', 'past_due', 'trialing'
            $table->string('billing_cycle')->default('monthly'); // 'monthly', 'yearly'
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('current_period_starts_at');
            $table->timestamp('current_period_ends_at');
            $table->timestamp('canceled_at')->nullable();
            $table->boolean('auto_renew')->default(true);
            $table->string('payment_provider')->nullable(); // 'stripe', 'paypal', etc.
            $table->string('payment_provider_id')->nullable(); // External ID
            $table->json('metadata')->nullable();
            $table->json('custom_limits')->nullable(); // For overriding plan limits
            $table->timestamps();

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreign('plan_id')
                ->references('id')
                ->on('plans')
                ->onDelete('restrict');
        });

        // Usage Quotas (current real-time tracking)
        Schema::create('usage_quotas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenant_id');
            $table->uuid('subscription_id');
            $table->integer('devices_count')->default(0);
            $table->integer('screens_count')->default(0);
            $table->integer('users_count')->default(0);
            $table->integer('storage_used_mb')->default(0);
            $table->integer('bandwidth_used_mb')->default(0);
            $table->json('additional_quotas')->nullable();
            $table->timestamp('reset_at')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreign('subscription_id')
                ->references('id')
                ->on('subscriptions')
                ->onDelete('cascade');
        });

        // Quota Add-ons (purchased additional capacity)
        Schema::create('quota_addons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenant_id');
            $table->uuid('subscription_id');
            $table->string('type'); // 'devices', 'screens', 'storage', 'bandwidth'
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->string('status'); // 'active', 'pending', 'expired'
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreign('subscription_id')
                ->references('id')
                ->on('subscriptions')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quota_addons');
        Schema::dropIfExists('usage_quotas');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plans');
    }
};
