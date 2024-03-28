<?php

namespace App\Models;

use App\Observers\OrderObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @class Order
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property int $info_id
 * @property int $amount
 * @property string $ordered_with_all
 * @property string $created_at
 * @property-read User $user
 * @property-read Info $info
 */
#[ObservedBy([OrderObserver::class])]
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'info_id',
        'amount',
        'ordered_with_all'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function info(): BelongsTo
    {
        return $this->belongsTo(Info::class, 'info_id', 'id');
    }

    /**
     * @return Attribute
     */
    public function createdAt(): Attribute
    {
        return new Attribute(
            get: fn($value) => Carbon::parse($value)->format('d.m.Y в H:i:s')
        );
    }

    /**
     * @return string
     */
    public function getPeriod(): string
    {
        $date = Carbon::parse($this->getRawOriginal('created_at'));

        $start = new Carbon('first day of last month');
        $end = new Carbon('last day of last month');

        $lastWeekEnd = new Carbon('last day of last week');
        $yesterday = new Carbon('yesterday');
        return match (true) {
            $date->gte($start->startOfMonth()) && $date->lte($end->endOfMonth()) => 'lm',
            $date->gt($lastWeekEnd->endOfWeek()) && $date->lt($yesterday->endOfDay()) => 'cw',
            $date->gt(Carbon::now()->startOfDay()) && $date->lt(Carbon::now()->endOfDay()) => 'cd',
            default => ''
        };
    }
}
