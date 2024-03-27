<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
/**
 * @class Role
 * @property integer $id
 * @property string $name
 * @property-read Permission $permissions
 */
class Role extends Model
{
    use HasFactory;

    protected $fillable = [
      'name'
    ];

    protected $with = ['permissions'];

    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
}
