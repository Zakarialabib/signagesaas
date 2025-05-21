<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->uuid('parent_id')->nullable()->after('id');
            $table->integer('version')->default(1)->after('parent_id');
            $table->boolean('is_variation')->default(false)->after('version');

            $table->foreign('parent_id')
                ->references('id')
                ->on('templates')
                ->onDelete('set null');

            $table->index(['parent_id', 'version']);
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['parent_id', 'version']);
            $table->dropColumn(['parent_id', 'version', 'is_variation']);
        });
    }
};
