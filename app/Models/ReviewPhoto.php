<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ReviewPhoto extends BaseModel
{
    /** @use HasFactory<\Database\Factories\ReviewPhotoFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'external_id',
        'review_id',
        'path',
        'thumbnail_path',
    ];

    protected $appends = ['url', 'thumbnail_url'];

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }

    public function getUrlAttribute(): string
    {
        if (!Storage::disk('public')->exists($this->path)) {
            return '';
        }
        
        return url(Storage::disk('public')->url($this->path));
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail_path && Storage::disk('public')->exists($this->thumbnail_path)) {
            return url(Storage::disk('public')->url($this->thumbnail_path));
        }
        return $this->url;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->external_id,
            'path' => $this->path,
            'url' => $this->url,
            'thumbnail_path' => $this->thumbnail_path,
            'thumbnail_url' => $this->thumbnail_url,
        ];
    }
}
