<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewPhoto extends BaseModel
{
    /** @use HasFactory<\Database\Factories\ReviewPhotoFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'external_id',
        'review_id',
        'filename',
    ];

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }
}
