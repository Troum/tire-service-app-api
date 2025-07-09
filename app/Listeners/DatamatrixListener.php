<?php

namespace App\Listeners;

use App\Events\DatamatrixCreatedEvent;
use App\Events\DatamatrixReadyEvent;
use App\Facades\DataMatrixGenerator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
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

        $tempDir = storage_path("app/temp/datamatrix/{$dm->id}");
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        foreach ($dm->codes as $code) {
            $pngBase64 = DataMatrixGenerator::generateDatamatrix($code);
            if ($pngBase64 === false) {
                continue;
            }
            file_put_contents(
                "{$tempDir}/{$code}.png",
                base64_decode($pngBase64)
            );
        }

        $zipName = "{$dm->id}.zip";
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
