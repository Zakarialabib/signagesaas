<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\File;

final class MoveLivewireToTenant extends Command
{
    protected $signature = 'tenant:move-livewire 
                            {source? : Source directory or file (default: app/Livewire)}
                            {--exclude= : Comma-separated list of directories to exclude}';

    protected $description = 'Move Livewire components to tenant structure';

    public function handle(): int
    {
        $source = $this->argument('source') ?? 'app/Livewire';
        $exclude = $this->option('exclude') ? explode(',', $this->option('exclude')) : ['SuperAdmin', 'Auth', 'Tenant'];

        $this->info('Moving Livewire components to tenant structure...');
        $this->info("Source: {$source}");
        $this->info('Excluded directories: '.implode(', ', $exclude));

        if ( ! File::exists($source)) {
            $this->error("Source directory or file does not exist: {$source}");

            return Command::FAILURE;
        }

        $sourceIsDirectory = File::isDirectory($source);

        if ($sourceIsDirectory) {
            $directoriesToProcess = collect(File::directories($source))
                ->map(fn ($dir) => basename($dir))
                ->filter(fn ($dir) => ! in_array($dir, $exclude))
                ->toArray();

            $this->info('Directories to process: '.implode(', ', $directoriesToProcess));

            foreach ($directoriesToProcess as $directory) {
                $this->processDirectory("{$source}/{$directory}", $directory);
            }
        } else {
            // Process single file
            $pathInfo = pathinfo($source);
            $directory = basename($pathInfo['dirname']);
            $filename = $pathInfo['filename'];

            if ( ! in_array($directory, $exclude)) {
                $this->processFile($source, $directory, $filename);
            } else {
                $this->warn("Skipping excluded file: {$source}");
            }
        }

        $this->info('Migration completed successfully');

        return Command::SUCCESS;
    }

    private function processDirectory(string $dirPath, string $dirName): void
    {
        $this->info("Processing directory: {$dirPath}");

        $files = File::files($dirPath);

        foreach ($files as $file) {
            $filename = pathinfo($file->getPathname(), PATHINFO_FILENAME);
            $this->processFile($file->getPathname(), $dirName, $filename);
        }

        // Process subdirectories
        $subdirectories = File::directories($dirPath);

        foreach ($subdirectories as $subdir) {
            $subdirName = basename($subdir);
            $this->processDirectory($subdir, "{$dirName}/{$subdirName}");
        }
    }

    private function processFile(string $filePath, string $directory, string $filename): void
    {
        $targetNamespace = "App\\Tenant\\Livewire\\{$directory}";
        $targetFilePath = "app/Tenant/Livewire/{$directory}/{$filename}.php";
        $targetDirectory = dirname($targetFilePath);

        // Create target directory if it doesn't exist
        if ( ! File::exists($targetDirectory)) {
            File::makeDirectory($targetDirectory, 0755, true);
        }

        $this->info("Moving {$filePath} to {$targetFilePath}");

        // Use Livewire's move command, which handles namespace changes
        $process = new Process([
            'php',
            'artisan',
            'livewire:move',
            str_replace('.php', '', str_replace('app/Livewire/', '', $filePath)),
            str_replace('app/Tenant/Livewire/', '', str_replace('.php', '', $targetFilePath)),
        ]);

        $process->run();

        if ($process->isSuccessful()) {
            $this->info("Successfully moved component: {$filePath}");
            $this->line($process->getOutput());
        } else {
            $this->error("Failed to move component: {$filePath}");
            $this->error($process->getErrorOutput());
        }
    }
}
