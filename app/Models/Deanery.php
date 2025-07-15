<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deanery extends Model
{
    protected $fillable = ['name', 'placemark', 'updated_at'];

    /**
     * @return HasMany<Parish, $this>
     */
    public function parishes(): HasMany
    {
        return $this->hasMany(Parish::class);
    }

    /**
     * @return BelongsTo<Diocese, $this>
     */
    public function diocese(): BelongsTo
    {
        return $this->belongsTo(Diocese::class);
    }
}
