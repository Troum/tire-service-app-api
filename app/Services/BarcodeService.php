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
         return DNS2D::getBarcodeHTML($barcode, 'QRCODE');
     }
}
