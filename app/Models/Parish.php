<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property Deanery                         $deanery
 * @property Church[]                        $churches
 * @property int                             $id
 * @property int                             $deanery_id
 * @property string                          $url
 * @property string                          $name
 * @property string                          $placemark
 * @property null|\Illuminate\Support\Carbon $updated_at
 * @property null|\Illuminate\Support\Carbon $deleted_at
 */
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
