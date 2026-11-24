<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SystemLogs extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?int $navigationSort = 99;

    protected string $view = 'filament.pages.system-logs';

    public string $selectedFile = '';

    public string $levelFilter = 'all';

    public string $search = '';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('Super Admin') ?? false;
    }

    public static function getNavigationLabel(): string
    {
        return __('Error Logs');
    }

    public function getTitle(): string
    {
        return __('Error Logs');
    }

    public function mount(): void
    {
        $files = $this->getLogFiles();
        $this->selectedFile = $files[0]['name'] ?? '';
    }

    public function downloadSelected(): ?BinaryFileResponse
    {
        abort_unless(static::canAccess(), 403);

        $path = $this->resolveSafeLogPath($this->selectedFile);
        if (!$path) {
            return null;
        }

        return response()->download($path, basename($path));
    }

    public function clearSelected(): void
    {
        abort_unless(static::canAccess(), 403);

        $path = $this->resolveSafeLogPath($this->selectedFile);
        if (!$path) {
            return;
        }

        File::put($path, '');

        Notification::make()
            ->title(app()->getLocale() === 'ar' ? 'تم مسح ملف السجل' : 'Log file cleared')
            ->success()
            ->send();
    }

    /**
     * @return array<int, array{name: string, size: string, updated_at: string}>
     */
    protected function getLogFiles(): array
    {
        $logDir = storage_path('logs');
        if (!File::isDirectory($logDir)) {
            return [];
        }

        return collect(File::files($logDir))
            ->filter(fn (\SplFileInfo $file) => strtolower($file->getExtension()) === 'log')
            ->sortByDesc(fn (\SplFileInfo $file) => $file->getMTime())
            ->values()
            ->map(fn (\SplFileInfo $file) => [
                'name' => $file->getFilename(),
                'size' => number_format($file->getSize() / 1024, 1) . ' KB',
                'updated_at' => date('Y-m-d H:i', $file->getMTime()),
            ])
            ->all();
    }

    protected function resolveSafeLogPath(string $filename): ?string
    {
        if ($filename === '' || str_contains($filename, '/') || str_contains($filename, '\\') || str_contains($filename, '..')) {
            return null;
        }

        $path = storage_path('logs/' . $filename);

        return File::isFile($path) ? $path : null;
    }

    /**
     * @return array<int, array{timestamp: string, env: string, level: string, message: string, stack: string}>
     */
    protected function parseEntries(string $path): array
    {
        $size = filesize($path);
        $maxBytes = 512 * 1024; // Read up to last 512 KB for fast rendering

        if ($size > $maxBytes) {
            $handle = fopen($path, 'rb');
            fseek($handle, -$maxBytes, SEEK_END);
            $content = fread($handle, $maxBytes);
            fclose($handle);
        } else {
            $content = File::get($path);
        }

        $pattern = '/^\[(\d{4}-\d{2}-\d{2}[T\s]\d{2}:\d{2}:\d{2}[^\]]*)\]\s+([a-zA-Z0-9_-]+)\.([A-Z]+):\s(.*)$/m';
        $matches = [];
        preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE);

        if (empty($matches[0])) {
            return [];
        }

        $entries = [];
        $total = count($matches[0]);

        for ($i = 0; $i < $total; $i++) {
            $start = $matches[0][$i][1] + strlen($matches[0][$i][0]);
            $end = ($i + 1 < $total) ? $matches[0][$i + 1][1] : strlen($content);
            $stack = trim(substr($content, $start, $end - $start));

            $entries[] = [
                'timestamp' => $matches[1][$i][0],
                'env' => $matches[2][$i][0],
                'level' => $matches[3][$i][0],
                'message' => trim($matches[4][$i][0]),
                'stack' => $stack,
            ];
        }

        $entries = array_reverse($entries);

        return collect($entries)
            ->filter(function (array $entry) {
                if ($this->levelFilter === 'errors' && !in_array($entry['level'], ['ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY'], true)) {
                    return false;
                }
                if ($this->levelFilter === 'warnings' && $entry['level'] !== 'WARNING') {
                    return false;
                }
                if ($this->levelFilter === 'info' && !in_array($entry['level'], ['INFO', 'DEBUG', 'NOTICE'], true)) {
                    return false;
                }
                if (trim($this->search) !== '') {
                    $needle = mb_strtolower(trim($this->search));
                    $haystack = mb_strtolower($entry['message'] . ' ' . $entry['stack'] . ' ' . $entry['level']);
                    if (!str_contains($haystack, $needle)) {
                        return false;
                    }
                }

                return true;
            })
            ->take(100)
            ->values()
            ->all();
    }

    protected function getViewData(): array
    {
        $files = $this->getLogFiles();
        if ($this->selectedFile === '' && !empty($files)) {
            $this->selectedFile = $files[0]['name'];
        }

        $path = $this->resolveSafeLogPath($this->selectedFile);
        $entries = $path ? $this->parseEntries($path) : [];

        return [
            'files' => $files,
            'entries' => $entries,
        ];
    }
}
