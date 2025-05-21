<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

final class ConfigureTenantModels extends Command
{
    protected $signature = 'tenant:configure-models
                            {--dry-run : Run in dry mode without making changes}
                            {--path= : Specify a custom path to search (default: app)}';

    protected $description = 'Update namespaces and imports to use tenant models';

    // Maps from App\Models to App\Tenant\Models
    private array $modelMap = [];
    private array $updatedFiles = [];
    private bool $dryRun = false;

    public function handle(): int
    {
        $this->dryRun = $this->option('dry-run');
        $path = $this->option('path') ?? 'app';

        if ($this->dryRun) {
            $this->info('Running in dry-run mode. No changes will be made.');
        }

        // Build a map of models that have been moved to tenant namespace
        $this->buildModelMap();

        if (empty($this->modelMap)) {
            $this->warn('No tenant models found in app/Tenant/Models. Run tenant:move-models first.');

            return Command::FAILURE;
        }

        $this->info('Found '.count($this->modelMap).' tenant models.');
        $this->table(
            ['Original Model', 'Tenant Model'],
            collect($this->modelMap)->map(fn ($v, $k) => [$k, $v])->toArray()
        );

        // Search for files that might need updates
        $this->processDirectory($path);

        $this->info('Processed '.count($this->updatedFiles).' files.');

        if ( ! empty($this->updatedFiles)) {
            $this->table(
                ['Updated Files'],
                collect($this->updatedFiles)->map(fn ($f) => [$f])->toArray()
            );
        }

        return Command::SUCCESS;
    }

    private function buildModelMap(): void
    {
        $tenantModelPath = 'app/Tenant/Models';

        if ( ! File::exists($tenantModelPath)) {
            return;
        }

        $tenantModels = collect(File::files($tenantModelPath))
            ->filter(fn ($file) => $file->getExtension() === 'php')
            ->map(function ($file) {
                $className = $file->getFilenameWithoutExtension();

                return [
                    "App\\Models\\{$className}",
                    "App\\Tenant\\Models\\{$className}",
                ];
            })
            ->toArray();

        // Convert to associative array
        foreach ($tenantModels as [$original, $tenant]) {
            $this->modelMap[$original] = $tenant;
        }
    }

    private function processDirectory(string $path): void
    {
        // Skip the Tenant Models directory itself
        if ($path === 'app/Tenant/Models') {
            return;
        }

        // Process all PHP files in this directory
        $files = File::files($path);

        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $this->processFile($file->getPathname());
            }
        }

        // Process subdirectories
        $directories = File::directories($path);

        foreach ($directories as $directory) {
            $this->processDirectory($directory);
        }
    }

    private function processFile(string $filePath): void
    {
        $content = File::get($filePath);
        $originalContent = $content;
        $modified = false;

        // 1. Update use statements for tenant models
        foreach ($this->modelMap as $originalModel => $tenantModel) {
            $pattern = '/use\s+'.preg_quote($originalModel, '/').'(\s*;|\s+as)/';
            $replacement = 'use '.$tenantModel.'$1';

            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, $replacement, $content);
                $modified = true;
            }
        }

        // 2. Update fully qualified class references in code
        foreach ($this->modelMap as $originalModel => $tenantModel) {
            // Match the model name when it's used with full namespace
            $pattern = '/\b'.preg_quote($originalModel, '/').'\b(?!\\\\)/';

            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, $tenantModel, $content);
                $modified = true;
            }
        }

        // 3. Update direct model instantiation: new ModelName()
        foreach ($this->modelMap as $originalModel => $tenantModel) {
            $originalModelClass = class_basename($originalModel);
            $pattern = '/new\s+'.preg_quote($originalModelClass, '/').'\s*\(/';

            if (preg_match($pattern, $content) && ! str_contains($content, "use {$tenantModel};") && ! str_contains($content, "use {$tenantModel} as")) {
                // Add the tenant model use statement after the namespace declaration
                $pattern = '/(namespace\s+[^;]+;\s+)/';
                $replacement = "$1\nuse {$tenantModel};";
                $content = preg_replace($pattern, $replacement, $content);
                $modified = true;
            }
        }

        // Save changes if file was modified
        if ($modified && $content !== $originalContent) {
            $this->updatedFiles[] = $filePath;

            if ( ! $this->dryRun) {
                File::put($filePath, $content);
                $this->info("Updated file: {$filePath}");
            } else {
                $this->info("Would update file: {$filePath} (dry run)");
            }
        }
    }
}
