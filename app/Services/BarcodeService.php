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
         return 'data:image/jpeg;base64,' . DNS2D::getBarcodePNG($barcode, 'DATAMATRIX');
     }
}
