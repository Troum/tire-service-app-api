<?php

namespace App\Models;

use App\Enums\SeasonEnum;
use App\Observers\TypeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @class Type
 * @package App\Models
 * @property integer $id
 * @property integer $size_id
 * @property string $type
 * @property boolean $hide
 * @property SeasonEnum $season
 * @property-read Size $size
 */
#[ObservedBy([TypeObserver::class])]
class Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'size_id',
        'type',
        'season',
        'hide'
    ];

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'season' => SeasonEnum::class
        ];
    }

    /**
     * @return BelongsTo
     */
    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function info(): HasOne
    {
        return $this->hasOne(Info::class, 'type_id', 'id');
    }

}
