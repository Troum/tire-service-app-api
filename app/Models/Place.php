<?php

namespace App\Models;

use App\Enums\SeasonEnum;
use App\Observers\PlaceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @class Place
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property-read Info $infos
 * @method seasoned()
 */
#[ObservedBy([PlaceObserver::class])]
class Place extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
      'name',
      'address'
    ];

    /**
     * @return HasMany
     */
    public function infos(): HasMany
    {
        return $this->hasMany(Info::class, 'place_id', 'id');
    }

    /**
     * @return object
     */
    public function scopeSeasoned(): object
    {
        $winter = $this->infos->filter(function ($info) {
            return $info->type->season === SeasonEnum::WINTER;
        });
        $summer = $this->infos->filter(function ($info) {
            return $info->type->season === SeasonEnum::SUMMER;
        });
        $all = $this->infos->filter(function ($info) {
            return $info->type->season === SeasonEnum::ALL;
        });

        return (object)[
          'winter' => $winter->pluck('amount')->sum(),
          'summer' => $summer->pluck('amount')->sum(),
          'all' => $all->pluck('amount')->sum()
        ];
    }

    /**
     * @return HasMany
     */
    public function infosAll(): HasMany
    {
        return $this->hasMany(Info::class, 'place_id', 'id')
            ->whereHas('type', function ($query) {
                $query->type()->where('season', SeasonEnum::SUMMER);
            });
    }
}
