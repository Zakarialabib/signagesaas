<?php

declare(strict_types=1);

namespace App\Tenant\Models;

use App\Enums\ContentStatus;
use App\Enums\ContentType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

final class Content extends Model
{
    use HasUuids;
    use SoftDeletes;
    use HasFactory;
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'type',
        'status',
        'content_data',
        'template_id',
        'zone_id',
        'duration',
        'start_date',
        'end_date',
        'metadata',
        'settings',
    ];

    protected $casts = [
        'type'         => ContentType::class,
        'status'       => ContentStatus::class,
        'content_data' => 'array',
        'metadata'     => 'array',
        'settings'     => 'array',
        'start_date'   => 'datetime',
        'end_date'     => 'datetime',
        'duration'     => 'integer',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function screens(): BelongsToMany
    {
        return $this->belongsToMany(Screen::class, 'content_screen')
            ->using(ContentScreen::class)
            ->withPivot(['order', 'duration', 'settings'])
            ->withTimestamps();
    }

    public function schedules(): BelongsToMany
    {
        return $this->belongsToMany(Schedule::class)
            ->withPivot(['order', 'duration'])
            ->withTimestamps();
    }

    public function assets(): MorphMany
    {
        return $this->morphMany(Asset::class, 'assetable');
    }

    public function isActive(): bool
    {
        return $this->status === ContentStatus::ACTIVE
            && ( ! $this->start_date || $this->start_date->isPast())
            && ( ! $this->end_date || $this->end_date->isFuture());
    }

    public function getDuration(): int
    {
        return $this->duration ?? $this->template?->default_duration ?? 10;
    }

    public function getPreviewUrl(): string
    {
        return route('tenant.content.preview', $this->id);
    }

    public function getThumbnailUrl(): ?string
    {
        return $this->assets()->where('type', 'thumbnail')->first()?->url;
    }

    /** Get the screen that owns the content. */
    public function screen(): BelongsTo
    {
        return $this->belongsTo(Screen::class);
    }

    /** Get rendered HTML for this content. */
    public function getRenderedHtml(): string
    {
        // Implementation depends on content type
        return match ($this->type) {
            ContentType::IMAGE    => $this->renderImage(),
            ContentType::VIDEO    => $this->renderVideo(),
            ContentType::HTML     => $this->renderHtml(),
            ContentType::URL      => $this->renderUrl(),
            ContentType::RSS      => $this->renderRss(),
            ContentType::WEATHER  => $this->renderWeather(),
            ContentType::SOCIAL   => $this->renderSocial(),
            ContentType::CALENDAR => $this->renderCalendar(),
            ContentType::CUSTOM   => $this->renderCustom(),
            default               => '<div class="error">Unsupported content type</div>',
        };
    }

    /** Render image content */
    private function renderImage(): string
    {
        $url = $this->content_data['url'] ?? '';

        return "<img src=\"{$url}\" alt=\"{$this->name}\" style=\"width:100%;height:100%;object-fit:contain;\">";
    }

    /** Render video content */
    private function renderVideo(): string
    {
        $url = $this->content_data['url'] ?? '';

        return "<video autoplay loop muted style=\"width:100%;height:100%;object-fit:contain;\"><source src=\"{$url}\"></video>";
    }

    /** Render HTML content */
    private function renderHtml(): string
    {
        return $this->content_data['html'] ?? '';
    }

    /** Render URL content (iframe) */
    private function renderUrl(): string
    {
        $url = $this->content_data['url'] ?? '';

        return "<iframe src=\"{$url}\" style=\"width:100%;height:100%;border:none;\"></iframe>";
    }

    /** Render RSS content */
    private function renderRss(): string
    {
        $feedUrl = $this->content_data['feed_url'] ?? '';

        // For security, render a placeholder or use a server-side fetch and cache for real implementation
        return $feedUrl
            ? "<div class='rss-feed' data-feed-url='{$feedUrl}'>RSS Feed: <a href='{$feedUrl}' target='_blank'>{$feedUrl}</a></div>"
            : '<div class="text-gray-500">No RSS feed configured.</div>';
    }

    /** Render Weather content */
    private function renderWeather(): string
    {
        $location = $this->content_data['location'] ?? 'Unknown';
        $widget = $this->content_data['widget'] ?? null;

        // Placeholder for weather widget
        return $widget
            ? $widget
            : "<div class='weather-widget'>Weather for {$location}</div>";
    }

    /** Render Social content */
    private function renderSocial(): string
    {
        $platform = $this->content_data['platform'] ?? 'social';
        $handle = $this->content_data['handle'] ?? '';

        return $handle
            ? "<div class='social-feed'>Social Feed: {$platform} / {$handle}</div>"
            : '<div class="text-gray-500">No social feed configured.</div>';
    }

    /** Render Calendar content */
    private function renderCalendar(): string
    {
        $calendarUrl = $this->content_data['calendar_url'] ?? '';

        return $calendarUrl
            ? "<iframe src='{$calendarUrl}' style='width:100%;height:100%;border:none;'></iframe>"
            : '<div class="text-gray-500">No calendar configured.</div>';
    }

    /** Render Custom content */
    private function renderCustom(): string
    {
        return $this->content_data['html'] ?? '<div class="text-gray-500">No custom content provided.</div>';
    }
}
