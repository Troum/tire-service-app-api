<?php

namespace App\Models;

use App\Observers\SizeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @class Size
 * @package App\Models
 * @property integer $id
 * @property integer $size
 * @property-read Type[] $types
 */
#[ObservedBy([SizeObserver::class])]
class Size extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'size'
    ];

    /**
     * @return HasMany
     */
    public function types(): HasMany
    {
        return $this->hasMany(Type::class, 'size_id');
    }

    /**
     * @return Attribute
     */
    protected function size(): Attribute
    {
        return new Attribute(
            get: fn($value) => $value . "''"
        );
    }
}
