<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends BaseModel
{
    /** @use HasFactory<\Database\Factories\RestaurantFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['external_id', 'name', 'slug', 'address'];
    public $timestamps = true;

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->external_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'address' => $this->address,
            'reviews' => $this->reviews
        ];
    }

    public function toSoftArray(): array
    {
        return [
            'id' => $this->external_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'address' => $this->address,
        ];
    }
}
