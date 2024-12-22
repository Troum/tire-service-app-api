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
         $data = DNS2D::getBarcodePNG($barcode, 'DATAMATRIX');
         sleep(1);
         return 'data:image/png;base64,' . $data;
     }
}
