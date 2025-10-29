<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property Parish                          $parish
 * @property ChurchImage[]                   $images
 * @property ChurchCommunication[]           $communications
 * @property int                             $id
 * @property string                          $url
 * @property string                          $name
 * @property string                          $seo_description
 * @property string                          $seo_tags
 * @property float                           $latitude
 * @property float                           $longitude
 * @property int                             $parish_id
 * @property bool                            $drone_approval
 * @property bool                            $open_area
 * @property bool                            $contact_later
 * @property null|\Illuminate\Support\Carbon $updated_at
 * @property null|\Illuminate\Support\Carbon $deleted_at
 */
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
