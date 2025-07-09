<?php

namespace App\Facades;

use App\Services\BarcodeService;
use Illuminate\Support\Facades\Facade;

/**
 * @class Barcode
 * @package App\Facades
 * @method static string generateBarcodeQr(string $barcode)
 * @method static string generateDatamatrix(string $barcode)
 */
class DataMatrixGenerator extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return BarcodeService::class;
    }
}
