<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

final class MoveTenantModels extends Command
{
    protected $signature = 'tenant:move-models 
                            {source? : Source directory or file (default: app/Models)}
                            {--exclude= : Comma-separated list of models to exclude}';

    protected $description = 'Move models to tenant structure';

    public function handle(): int
    {
        $source = $this->argument('source') ?? 'app/Models';
        $exclude = $this->option('exclude') ? explode(',', $this->option('exclude')) : ['Tenant', 'SuperAdmin'];

        $this->info('Moving models to tenant structure...');
        $this->info("Source: {$source}");
        $this->info('Excluded models: '.implode(', ', $exclude));

        if ( ! File::exists($source)) {
            $this->error("Source directory or file does not exist: {$source}");

            return Command::FAILURE;
        }

        $sourceIsDirectory = File::isDirectory($source);

        if ($sourceIsDirectory) {
            $filesToProcess = collect(File::files($source))
                ->map(fn ($file) => $file->getPathname())
                ->filter(function ($file) use ($exclude) {
                    $basename = basename($file, '.php');

                    return ! in_array($basename, $exclude);
                })
                ->toArray();

            $this->info('Files to process: '.count($filesToProcess));

            foreach ($filesToProcess as $file) {
                $this->processFile($file);
            }
        } else {
            // Process single file
            $basename = basename($source, '.php');

            if ( ! in_array($basename, $exclude)) {
                $this->processFile($source);
            } else {
                $this->warn("Skipping excluded file: {$source}");
            }
        }

        $this->info('Migration completed successfully');

        return Command::SUCCESS;
    }

    private function processFile(string $filePath): void
    {
        $filename = pathinfo($filePath, PATHINFO_FILENAME);
        $targetFilePath = "app/Tenant/Models/{$filename}.php";
        $targetDirectory = dirname($targetFilePath);

        // Create target directory if it doesn't exist
        if ( ! File::exists($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true);
        }

        $this->info("Moving {$filePath} to {$targetFilePath}");

        // Read the content of the source file
        $content = File::get($filePath);

        // Update the namespace
        $content = $this->updateNamespace($content);

        // Add BelongsToTenant trait if not already present
        $content = $this->addTenantTrait($content, $filename);

        // Write the modified content to the target file
        File::put($targetFilePath, $content);

        $this->info("Successfully moved model: {$filePath}");
    }

    private function updateNamespace(string $content): string
    {
        // Replace the namespace
        return preg_replace(
            '/namespace App\\\\Models;/i',
            'namespace App\\Tenant\\Models;',
            $content
        );
    }

    private function addTenantTrait(string $content, string $modelName): string
    {
        // Check if BelongsToTenant trait is already used
        if (strpos($content, 'BelongsToTenant') !== false) {
            return $content;
        }

        // Add the use statement for BelongsToTenant if not already present
        if (strpos($content, 'use Stancl\\Tenancy\\Database\\Concerns\\BelongsToTenant;') === false) {
            $content = preg_replace(
                '/(namespace App\\\\Tenant\\\\Models;.*?)(\s*)(class\s+'.$modelName.')/s',
                '$1$2use Stancl\\Tenancy\\Database\\Concerns\\BelongsToTenant;$2$3',
                $content
            );
        }

        // Add the use BelongsToTenant trait statement inside the class and remove any existing tenant relationship method
        $content = preg_replace(
            '/(class\s+'.$modelName.'.*?{)/s',
            "$1\n    use BelongsToTenant;",
            $content
        );

        // Remove any existing tenant() method that might conflict with BelongsToTenant
        $content = preg_replace(
            '/\s*public\s+function\s+tenant\s*\(\s*\)\s*:\s*BelongsTo\s*{[^}]*return\s+\$this->belongsTo\(.*?Tenant::class.*?\);\s*}/s',
            '',
            $content
        );

        return $content;
    }
}
