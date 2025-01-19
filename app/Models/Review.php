<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $rating
 */
class Review extends BaseModel
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'external_id',
        'restaurant_id',
        'user_id',
        'title',
        'description',
        'rating',
    ];
    public $timestamps = true;

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewPhotos(): HasMany
    {
        return $this->hasMany(ReviewPhoto::class);
    }

    public function setRatingAttribute($value): void
    {
        $this->attributes['rating'] = ($value >= 1 && $value <= 5) ? $value : 0;
    }

    public function getRatingAttribute($value): int|null
    {
        return ($value >= 1 && $value <= 5) ? $value : null;
    }

    public function toArray()
    {
        return [
            'id' => $this->external_id,
            'rating' => $this->rating,
            'title' => $this->title,
            'description' => $this->description,
            'photos' => $this->reviewPhotos()
        ];
    }
}
