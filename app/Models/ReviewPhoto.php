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
    ];

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }

    public function getUrlAttribute(): string
    {
        return url(Storage::disk('public')->url($this->path));
    }

    public function toArray(): array
    {
        return [
            'id' => $this->external_id,
            'path' => $this->path,
            'url' => $this->url
        ];
    }
}
