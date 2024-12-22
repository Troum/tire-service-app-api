<?php

namespace App\Models;

use App\Casts\QrCodeCast;
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
 * @property string $qr_code_hash
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
        'qr_code_hash',
        'amount',
        'price'
    ];

    protected $casts = [
      'qr_code_hash' => QrCodeCast::class,
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
