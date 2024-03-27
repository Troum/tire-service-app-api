<?php

namespace App\Models;

use App\Observers\InfoObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use function url;

/**
 * @class Info
 * @package App\Models
 * @property integer $id
 * @property integer $type_id
 * @property string $name
 * @property string $image_url
 * @property integer $amount
 * @property float $price
 * @property-read Type $type
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
        'amount',
        'price'
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
