<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @class Datamatrix
 * @package App\Models
 * @property int $id
 * @property string $tireName
 * @property mixed $codes
 * @property string $url
 */
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
     * @return Attribute
     */
    protected function codes(): Attribute
    {
        return new Attribute(
            get: fn($value) => json_decode($value),
            set: fn($value) => json_encode($value),
        );
    }
}
