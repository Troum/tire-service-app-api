<?php

namespace App\Services;

use Milon\Barcode\Facades\DNS2DFacade as DNS2D;

class BarcodeService
{
    /**
     * @param string $barcode
     * @return string|null
     */
     public function generateBarcodeQr(string $barcode): ?string
     {
         return DNS2D::getBarcodeHTML($barcode, 'QRCODE', 7, 7);
     }

    /**
     * @param string $barcode
     * @return false|string
     */
     public function generateDatamatrix(string $barcode): false|string
     {
         return DNS2D::getBarcodePNG($barcode, 'DATAMATRIX', 10, 10);
     }
}
