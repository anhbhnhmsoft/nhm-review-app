<?php

namespace App\Utils;


use Illuminate\Support\Carbon;

final class HelperFunction
{
    public static function getTimestampAsId(): int
    {
        // Get microtime float
        $microFloat = microtime(true);
        $microTime = Carbon::createFromTimestamp($microFloat);
        $formatted = $microTime->format('ymdHisu');
        usleep(100);
        return (int)$formatted;
    }

    public static function generateURLFilePath(?string $filePath): ?string
    {
        if (!empty($filePath)) {
            return route('loadfile', ['file_path' => $filePath]);
        }
        return null;
    }

    public static function generateUiAvatarUrl(?string $name, ?string $email): string
    {
        $text = $name ?: ($email ?: 'User');
        return 'https://ui-avatars.com/api/?name=' . urlencode($text) . '&background=random&color=random';
    }
}
