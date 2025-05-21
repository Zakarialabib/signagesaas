<?php

declare(strict_types=1);

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

final class ContentScreen extends Pivot
{
    protected $table = 'content_screen';

    protected $casts = [
        'settings' => 'array',
    ];
}
