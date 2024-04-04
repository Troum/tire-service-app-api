<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    /**
     * @param string $templateUrl
     * @param array $data
     * @param string $path
     * @param string $name
     * @return string
     */
    public static function makeFile(string $templateUrl, array $data, string $path = 'public/files/', string $name = 'file.pdf'): string
    {
        $content = Pdf::loadView($templateUrl, $data)->download()->getOriginalContent();

        Storage::put($path . $name, $content);

        return self::getFileUrl($path . $name);
    }

    /**
     * @param string $path
     * @return Application|string|UrlGenerator|\Illuminate\Contracts\Foundation\Application
     */
    private static function getFileUrl(string $path): Application|string|UrlGenerator|\Illuminate\Contracts\Foundation\Application
    {
        return url($path);
    }
}
