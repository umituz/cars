<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Whtht\PerfectlyCache\Traits\PerfectlyCachable;

/**
 * Class Car
 * @package App\Models
 */
class Car extends Model
{
    use HasFactory, PerfectlyCachable, SoftDeletes;

    protected $guarded = [];

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return HasOne
     */
    public function brand(): HasOne
    {
        return $this->hasOne(Brand::class);
    }
}
