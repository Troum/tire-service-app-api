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

        // эталонная высота DataMatrix, при которой шрифты 64 и 22 выглядят хорошо
        $refHeight = 200;

        foreach ($dm->codes as $code) {
            // 1) Генерируем DataMatrix
            $pngBase64 = DataMatrixGenerator::generateDatamatrix($code);
            if ($pngBase64 === false) {
                continue;
            }

            // 2) Читаем картинку
            $img = Image::read(base64_decode($pngBase64));
            $origW = $img->width();
            $origH = $img->height();

            // 3) Вычисляем коэффициент масштаба относительно эталонной высоты
            $scale = $origH / $refHeight;

            // 4) Динамические размеры шрифтов (минимум 12px и 10px соответственно)
            $codeFontSize = max(12, (int)round(48 * $scale));
            $nameFontSize = max(10, (int)round(22 * $scale));

            // 5) Line-height для каждого текста
            $codeLineHeight = $codeFontSize * 2;
            $nameLineHeight = $nameFontSize * 1.25;

            // 6) Паддинг вокруг текста
            $padding = 5 * $scale;

            // 7) Итоговые размеры холста
            //    — делаем квадрат чуть больше для матрицы: *1.5
            //    — ниже добавляем место для двух строк текста + паддинг
            $canvasW = $origW * 1.5;
            $canvasH = $origH * 1.5 + $codeLineHeight + $nameLineHeight + $padding * 2;

            // 8) Создаём белый холст нужного размера
            $canvas = Image::create($canvasW, $canvasH)->fill('#ffffff');

            // 9) Вставляем DataMatrix сверху с небольшим отступом
            $canvas->place($img, 'top', 0, (int)$padding + 5);

            // 10) Позиции Y для двух текстов
            // Код шины — примерно на 60% от высоты оригинальной матрицы
            $yCode = $origH * 1.25;
            // Название — чуть ниже нижнего края матрицы
            $yName = $origH * 1.65 + $nameLineHeight / 2;

            // 11) Рисуем код шины
            $canvas->text($dm->tireCode, $canvasW / 2, $yCode, function($font) use (
                $codeFontSize, $origW
            ) {
                $font->file(public_path('fonts/dejavu-sans/ttf/DejaVuSansCondensed.ttf'));
                $font->size($codeFontSize);
                $font->align('center');
                $font->valign('middle');
                $font->lineHeight(2.5);
                $font->wrap($origW);
            });

            // 12) Рисуем название шины
            $canvas->text($dm->tireName, $canvasW / 2, $yName, function($font) use (
                $nameFontSize, $origW
            ) {
                $font->file(public_path('fonts/dejavu-sans/ttf/DejaVuSansCondensed.ttf'));
                $font->size($nameFontSize);
                $font->align('center');
                $font->valign('middle');
                $font->lineHeight(1.45);
                $font->wrap($origW);
            });

            // 13) Сохраняем PNG
            $canvas->save("{$tempDir}/{$code}.png");
        }

        // Создаем zip со всеми картинками
        $zipName = $dm->zipName;
        $zipPath = storage_path("app/public/datamatrix/{$zipName}");
        Storage::makeDirectory('public/datamatrix');

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            foreach (scandir($tempDir) as $file) {
                if (in_array($file, ['.', '..'])) {
                    continue;
                }
                $zip->addFile("{$tempDir}/{$file}", $file);
            }
            $zip->close();
        }

        // Удаляем временную папку
        $this->deleteDirectory($tempDir);

        // Сохраняем URL и шлём эвент «готово»
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
