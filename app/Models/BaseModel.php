<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    public static function findByExternalId(string $id): ?BaseModel
    {
        $query = self::where('external_id', $id)->first();
        if (in_array(SoftDeletes::class, class_uses(self::class))) {
            $query->whereNull('deleted_at');
        }
        return $query->first();
    }
}
