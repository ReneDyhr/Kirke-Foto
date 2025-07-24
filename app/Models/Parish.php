<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parish extends Model
{
    use SoftDeletes;

    protected $fillable = ['deanery_id', 'url', 'name', 'placemark', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Deanery, $this>
     */
    public function deanery(): BelongsTo
    {
        return $this->belongsTo(Deanery::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Church, $this>
     */
    public function churches(): HasMany
    {
        return $this->hasMany(Church::class);
    }
}
