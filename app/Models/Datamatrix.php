<?php

namespace App\Models;

use App\Observers\DatamatrixObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @class Datamatrix
 * @package App\Models
 * @property int $id
 * @property string $tireName
 * @property string $zipName
 * @property mixed $codes
 * @property string $url
 */
#[ObservedBy([DatamatrixObserver::class])]
class Datamatrix extends Model
{
    /**
     * @var string
     */
    protected $table = 'data_matrices';

    /**
     * @var string[]
     */
    protected $fillable = [
      'tireName',
      'codes',
      'url'
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'zipName'
    ];

    /**
     * @return Attribute
     */
    protected function codes(): Attribute
    {
        return new Attribute(
            get: fn($value) => json_decode($value),
            set: fn($value) => json_encode($value),
        );
    }

    /**
     * @return string
     */
    public function getZipNameAttribute(): string
    {
        return Str::of($this->tireName)->trim()->slug('_') . "_{$this->id}.zip";
    }
}
