<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

final class CreateTenantMigration extends Command
{
    protected $signature = 'tenant:migration 
                           {name : The name of the migration}
                           {--table= : The name of the table to create}
                           {--create : Create a new table migration}
                           {--model= : Create migration based on model name}';

    protected $description = 'Create a migration file for a tenant model with proper tenant_id foreign key';

    public function handle(): int
    {
        $name = $this->argument('name');
        $table = $this->option('table');
        $create = $this->option('create');
        $model = $this->option('model');

        // If model is specified, derive table name from it
        if ($model) {
            $table = Str::snake(Str::pluralStudly(class_basename($model)));
            $create = true;
        }

        // If table is not specified and the create option is set,
        // derive table name from the migration name
        if ( ! $table && $create) {
            $table = Str::snake(Str::pluralStudly(
                preg_replace('/[0-9_]+/', '', $name)
            ));
        }

        if ( ! $table) {
            $this->error('Table name is required for tenant migration.');

            return Command::FAILURE;
        }

        // Format the migration name
        $datePrefix = date('Y_m_d_His');
        $migrationName = "{$datePrefix}_create_{$table}_table.php";
        $migrationPath = database_path("migrations/{$migrationName}");

        // Create the migration content
        $migrationContent = $this->getMigrationContent($table);

        // Write the migration file
        File::put($migrationPath, $migrationContent);

        $this->info("Tenant migration created successfully: {$migrationName}");

        return Command::SUCCESS;
    }

    private function getMigrationContent(string $table): string
    {
        return <<<EOT
            <?php

            use Illuminate\Database\Migrations\Migration;
            use Illuminate\Database\Schema\Blueprint;
            use Illuminate\Support\Facades\Schema;

            return new class extends Migration
            {
                /**
                 * Run the migrations.
                 */
                public function up(): void
                {
                    Schema::create('{$table}', function (Blueprint \$table) {
                        \$table->uuid('id')->primary();
                        \$table->string('tenant_id');
                        // Add your columns here
                        
                        \$table->timestamps();
                        \$table->softDeletes();
                        
                        // Add foreign key constraint to tenant_id
                        \$table->foreign('tenant_id')
                            ->references('id')
                            ->on('tenants')
                            ->onDelete('cascade');
                    });
                }

                /**
                 * Reverse the migrations.
                 */
                public function down(): void
                {
                    Schema::dropIfExists('{$table}');
                }
            };
            EOT;
    }
}
