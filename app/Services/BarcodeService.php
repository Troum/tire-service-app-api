<?php

namespace App\Services;

use Milon\Barcode\Facades\DNS2DFacade as DNS2D;

class BarcodeService
{
    /**
     * @param string $barcode
     * @return string
     */
     public function generateBarcodeQr(string $barcode): string
     {
         return 'data:image/png;base64,' . DNS2D::getBarcodePNG($barcode, 'DATAMATRIX');
     }
}