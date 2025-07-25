<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diocese extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'placemark', 'updated_at'];

    /**
     * @return HasMany<Deanery, $this>
     */
    public function deaneries(): HasMany
    {
        return $this->hasMany(Deanery::class);
    }
}
