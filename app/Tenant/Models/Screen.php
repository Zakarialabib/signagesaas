<?php

declare(strict_types=1);

namespace App\Tenant\Models;

use App\Enums\ScreenOrientation;
use App\Enums\ScreenResolution;
use App\Enums\ScreenStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

final class Screen extends Model
{
    use HasUuids;
    use SoftDeletes;
    use HasFactory;
    use BelongsToTenant;

    protected $fillable = [
        'name',
        'description',
        'status',
        'resolution',
        'orientation',
        'device_id',
        'location',
        'settings',
        'tenant_id',
    ];

    protected $casts = [
        'settings'    => 'array',
        'location'    => 'array',
        'status'      => ScreenStatus::class,
        'orientation' => ScreenOrientation::class,
        'resolution'  => ScreenResolution::class,
    ];

    /** Get the device that owns the screen. */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /** Get the contents for the screen via pivot. */
    public function contents()
    {
        return $this->belongsToMany(Content::class, 'content_screen')
            ->using(ContentScreen::class)
            ->withPivot(['order', 'duration', 'settings'])
            ->withTimestamps();
    }

    /** Get active contents for the screen ordered by display order. */
    public function activeContents(): HasMany
    {
        return $this->contents()
            ->where('status', 'active')
            ->orderBy('order');
    }

    /** Generate a preview URL for this screen. */
    public function getPreviewUrl(): string
    {
        return route('screen.preview', ['screen' => $this->id]);
    }

    /** Get the display URL for the screen. */
    public function getDisplayUrl(): string
    {
        return route('screen.display', ['screen' => $this->id]);
    }

    /** Check if the screen is active. */
    public function isActive(): bool
    {
        return $this->status === ScreenStatus::ACTIVE;
    }

    /** Check if the screen is in maintenance mode. */
    public function isInMaintenance(): bool
    {
        return $this->status === ScreenStatus::MAINTENANCE;
    }

    /** Check if the screen is inactive. */
    public function isInactive(): bool
    {
        return $this->status === ScreenStatus::INACTIVE;
    }

    /** Check if the screen is in landscape orientation. */
    public function isLandscape(): bool
    {
        return $this->orientation === ScreenOrientation::LANDSCAPE;
    }

    /** Check if the screen is in portrait orientation. */
    public function isPortrait(): bool
    {
        return $this->orientation === ScreenOrientation::PORTRAIT;
    }

    /** Get width and height as an array. */
    public function getDimensions(): array
    {
        $parts = explode('x', $this->resolution->value);

        return [
            'width'  => (int) $parts[0],
            'height' => (int) $parts[1],
        ];
    }

    /** Get screen width. */
    public function getWidth(): int
    {
        return $this->getDimensions()['width'];
    }

    /** Get screen height. */
    public function getHeight(): int
    {
        return $this->getDimensions()['height'];
    }

    /** Get the aspect ratio of the screen. */
    public function getAspectRatio(): string
    {
        $dimensions = $this->getDimensions();
        $gcd = $this->gcd($dimensions['width'], $dimensions['height']);

        return ($dimensions['width'] / $gcd).':'.($dimensions['height'] / $gcd);
    }

    /** Calculate greatest common divisor (for aspect ratio). */
    private function gcd(int $a, int $b): int
    {
        while ($b !== 0) {
            $remainder = $a % $b;
            $a = $b;
            $b = $remainder;
        }

        return $a;
    }
}
