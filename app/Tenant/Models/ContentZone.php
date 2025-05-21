<?php

declare(strict_types=1);

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

final class ContentZone extends Pivot
{
    protected $table = 'content_zone';

    protected $casts = [
        'settings' => 'array',
    ];
}
