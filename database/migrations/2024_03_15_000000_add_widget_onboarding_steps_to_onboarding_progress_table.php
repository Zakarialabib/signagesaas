<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::table('onboarding_progress', function (Blueprint $table) {
            $table->boolean('first_widget_content_created')->default(false);
            $table->boolean('widget_content_assigned_to_template')->default(false);
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::table('onboarding_progress', function (Blueprint $table) {
            $table->dropColumn('first_widget_content_created');
            $table->dropColumn('widget_content_assigned_to_template');
        });
    }
};
