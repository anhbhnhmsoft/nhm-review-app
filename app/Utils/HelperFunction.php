<?php

namespace App\Utils;


use Carbon\CarbonInterface;
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

    public static function generateURLImagePath(?string $filePath): ?string
    {
        if (!empty($filePath)) {
            return route('public_image', ['file_path' => $filePath]);
        }
        return null;
    }

    public static function generateURLVideoPath(?string $filePath): ?string
    {
        if (!empty($filePath)) {
            return route('public_video', ['file_path' => $filePath]);
        }
        return null;
    }

    public static function generateUiAvatarUrl(?string $name, ?string $email): string
    {
        $text = $name ?: ($email ?: 'User');
        return 'https://ui-avatars.com/api/?name=' . urlencode($text) . '&background=random&color=random';
    }

    public static function humanReviewTime($time, string $dateFormat = 'd/m/Y H:i', int $daysThreshold = 7): string
    {
        Carbon::setLocale('vi');
        $c = $time instanceof Carbon ? $time : Carbon::parse($time);
        $now = Carbon::now();
        // Nếu cột thời gian nằm ở tương lai (lệch timezone/clock), ép về hiện tại tối thiểu 0.
        if ($c->greaterThan($now)) {
            // Bạn có thể chọn hiển thị "vài giây trước" thay vì "trong X phút"
            return 'Đã đánh giá vài giây trước';
        }
        if ($c->diffInDays($now) >= $daysThreshold) {
            return 'Đã đánh giá vào lúc ' . $c->format($dateFormat);
        }

        // ví dụ: "5 phút trước", "1 giờ trước", "vài giây trước"
        $rel = $c->diffForHumans(
            $now,
            CarbonInterface::DIFF_RELATIVE_TO_NOW, // “… trước” / “trong …”
            false,                                 // không dùng short form
            1                                      // độ chính xác (1 đơn vị: phút, giờ, ngày)
        );
        return 'Đã đánh giá ' . $rel;
    }

    public static function avgRatingReview($location, $space, $quality, $serve): float
    {
        return round((($location + $space + $quality + $serve) / 4),2);
    }
}
