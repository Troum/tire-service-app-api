<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @class Permission
 * @property integer $id
 * @property string $name
 */
class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];
}
