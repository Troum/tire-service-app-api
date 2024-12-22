<?php

namespace App\Casts;

use App\Facades\Barcode;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class QrCodeCast implements CastsAttributes
{
    /**
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return string
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): string
    {
        return $value;
    }

    /**
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return Barcode::generateBarcodeQr($value);
    }
}
