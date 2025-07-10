<?php

namespace App\Listeners;

use App\Events\DatamatrixCreatedEvent;
use App\Events\DatamatrixReadyEvent;
use App\Facades\DataMatrixGenerator;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class DatamatrixListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DatamatrixCreatedEvent $event): void
    {
        $dm = $event->datamatrix;

        $tempDir = storage_path("app/temp/datamatrix/{$dm->zipName}");
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        foreach ($dm->codes as $code) {
            $pngBase64 = DataMatrixGenerator::generateDatamatrix($code);

            if ($pngBase64 === false) {
                continue;
            }

            $img = Image::read(base64_decode($pngBase64));

            $w = $img->width() * 1.5;
            $h = $img->height() * 1.5;
            $fontSize = 28;
            $padding  = 5;
            $textHeight = $fontSize * 1.25;

            $canvas = Image::create($w, $h + $textHeight + $padding)->fill('#ffffff');

            $canvas->place($img, 'top', 0, 20);

            $canvas->text($dm->tireName, $w/2, $h - $padding, function($font) use ($img) {
                $font->file(public_path('fonts/arial/arialmt.ttf'));
                $font->size(28);
                $font->align('center');
                $font->valign('middle');
                $font->lineHeight(1.45);
                $font->wrap($img->width());
            });

            $canvas->save("{$tempDir}/{$code}.png");
        }

        $zipName = $dm->zipName;
        $zipPath = storage_path("app/public/datamatrix/{$zipName}");
        Storage::makeDirectory('public/datamatrix');

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            foreach (scandir($tempDir) as $file) {
                if (in_array($file, ['.', '..'])) continue;
                $zip->addFile("{$tempDir}/{$file}", $file);
            }
            $zip->close();
        }

        $this->deleteDirectory($tempDir);

        $dm->url = Storage::url("public/datamatrix/{$zipName}");
        $dm->save();

        broadcast(new DatamatrixReadyEvent($dm));
    }

    protected function deleteDirectory(string $dir): void
    {
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            is_dir($path)
                ? $this->deleteDirectory($path)
                : unlink($path);
        }
        rmdir($dir);
    }
}
