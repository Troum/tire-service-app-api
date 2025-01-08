<?php

namespace App\Casts;

use App\Facades\DataMatrixGenerator;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DatamatrixCodeCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): array
    {
        $value = json_decode($value, false);

        return array_map(function ($item) {
            return [
                'id' => $item->id,
                'code' => DataMatrixGenerator::generateBarcodeQr($item->code),
            ];
        }, $value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string|false
    {
        if (!is_array($value)) {
            $value = explode(',', Str::of($value)->replace(' ',''));
            $value = array_map(function ($item) {
                return [
                    'id' => Str::uuid(),
                    'code' => $item,
                ];
            }, $value);
        }
        return json_encode($value);
    }
}
