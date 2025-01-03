<?php

namespace App\Models;

use App\Casts\DatamatrixCodeCast;
use App\Observers\InfoObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @class Info
 * @package App\Models
 * @property integer $id
 * @property integer $type_id
 * @property string $name
 * @property string $image_url
 * @property string $codes
 * @property integer $amount
 * @property float $price
 * @property-read Type $type
 * @property-read Place $place
 * @method static create(...$params)
 */
#[ObservedBy([InfoObserver::class])]
class Info extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'place_id',
        'name',
        'image_url',
        'codes',
        'amount',
        'price'
    ];

    protected $casts = [
        'codes' => DatamatrixCodeCast::class,
    ];

    /**
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class, 'place_id', 'id');
    }
}
