<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ListPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:list {pattern? : Optional pattern to filter permissions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all permissions in the system';

    /** Execute the console command. */
    public function handle()
    {
        $pattern = $this->argument('pattern');

        $query = DB::table('permissions')
            ->select(['id', 'name', 'description', 'guard_name']);

        if ($pattern) {
            $query->where('name', 'like', "%{$pattern}%");
        }

        $permissions = $query->get();

        if ($permissions->isEmpty()) {
            $this->info('No permissions found');

            return 0;
        }

        $headers = ['ID', 'Name', 'Description', 'Guard'];

        $rows = $permissions->map(function ($permission) {
            return [
                $permission->id,
                $permission->name,
                $permission->description ?? 'No description',
                $permission->guard_name,
            ];
        })->toArray();

        $this->table($headers, $rows);

        return 0;
    }
}
