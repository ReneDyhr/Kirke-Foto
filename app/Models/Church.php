<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Church extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'url',
        'name',
        'seo_description',
        'seo_tags',
        'latitude',
        'longitude',
        'parish_id',
        'drone_approval',
        'open_area',
        'contact_later',
        'updated_at',
    ];

    protected $casts = [
        'drone_approval' => 'boolean',
        'open_area' => 'boolean',
        'contact_later' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Parish, $this>
     */
    public function parish(): BelongsTo
    {
        return $this->belongsTo(Parish::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<ChurchImage, $this>
     */
    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ChurchImage::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<ChurchCommunication, $this>
     */
    public function communications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ChurchCommunication::class);
    }
}
