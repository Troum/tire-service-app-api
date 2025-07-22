<?php

namespace App\Listeners;

use App\Events\DatamatrixCreatedEvent;
use App\Events\DatamatrixReadyEvent;
use App\Facades\DataMatrixGenerator;
use Exception;
use Illuminate\Support\Str;
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
        try {
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
                $codeFontSize = max(12, (int)round(48 * $scale / 1.9)); // Уменьшен в 1.9 раза
                $nameFontSize = max(10, (int)round(22 * $scale));

                // 5) Line-height для каждого текста
                $codeLineHeight = $codeFontSize * 1.5; // Уменьшен межстрочный интервал
                $nameLineHeight = $nameFontSize * 1.25;

                // 6) Паддинг вокруг текста
                $padding = 5 * $scale;

                // 7) Рассчитываем реальную потребность в пространстве
                $textBlocksHeight = 0;
                $yPositions = [];

                if ($dm->tireCode) {
                    $textBlocksHeight += $codeLineHeight + $padding * 1.5; // Увеличен отступ
                    $yPositions['code'] = $origH * 1.35; // Увеличен отступ сверху
                }

                $textBlocksHeight += $nameLineHeight + $padding * 2; // Добавлен дополнительный отступ
                $yPositions['name'] = $dm->tireCode
                    ? $origH * 1.7 + $nameLineHeight / 2  // Немного уменьшено расстояние
                    : $origH * 1.25 + $nameLineHeight / 2;  // Поднимаем выше при отсутствии кода

                // Обновляем размер холста
                $canvasW = $origW * 1.5;
                $canvasH = $origH * 1.5 + $textBlocksHeight + $padding * 1.4; // Добавлен отступ снизу

                // 8) Создаём белый холст обновлённого размера
                $canvas = Image::create($canvasW, $canvasH)->fill('#ffffff');

                // 9) Вставляем DataMatrix сверху с небольшим отступом
                $canvas->place($img, 'top', 0, (int)$padding + 5);

                // 10) Рисуем код шины (если есть)
                if ($dm->tireCode) {
                    $canvas->text($dm->tireCode, $canvasW / 2, $yPositions['code'], function($font) use (
                        $codeFontSize, $origW
                    ) {
                        $font->file(public_path('fonts/dejavu-sans/ttf/DejaVuSansCondensed.ttf'));
                        $font->size($codeFontSize);
                        $font->align('center');
                        $font->valign('middle');
                        $font->lineHeight(1.8); // Уменьшен межстрочный интервал
                        $font->wrap($origW);
                    });
                }

                // 11) Рисуем название шины
                $canvas->text($dm->tireName, $canvasW / 2, $yPositions['name'], function($font) use (
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
                $name = Str::of($code)->slug();
                $canvas->save("{$tempDir}/{$name}.png");
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
        } catch (Exception $exception) {
            $dm->delete();
        }

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
