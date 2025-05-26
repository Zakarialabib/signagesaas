<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migration. */
    public function up(): void
    {
        if ( ! Schema::hasTable('settings')) {
            // Recreate the settings table if it doesn't exist
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key');
                $table->string('tenant_id');
                $table->text('value')->nullable();
                $table->timestamps();

                // Add unique constraint to prevent duplicate keys per tenant
                $table->unique(['key', 'tenant_id']);

                // Foreign key to tenants table
                $table->foreign('tenant_id')
                    ->references('id')
                    ->on('tenants')
                    ->onDelete('cascade');
            });
        }
    }

    /** Reverse the migration. */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
