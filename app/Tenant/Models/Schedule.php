<?php

declare(strict_types=1);

namespace App\Tenant\Models;

use App\Enums\ScheduleStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

final class Schedule extends Model
{
    use HasFactory;
    use HasUuids;
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'screen_id',
        'status',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'days_of_week',
        'priority',
    ];

    protected $casts = [
        'start_date'   => 'date',
        'end_date'     => 'date',
        'start_time'   => 'datetime',
        'end_time'     => 'datetime',
        'days_of_week' => 'array',
        'status'       => ScheduleStatus::class,
        'priority'     => 'integer',
    ];

    protected $attributes = [
        'days_of_week' => '[]',
    ];

    public function screen(): BelongsTo
    {
        return $this->belongsTo(Screen::class);
    }

    public function contents(): BelongsToMany
    {
        return $this->belongsToMany(Content::class)
            ->withPivot(['order', 'duration'])
            ->withTimestamps();
    }
}
