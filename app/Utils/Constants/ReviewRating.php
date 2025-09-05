<?php

namespace App\Utils\Constants;

enum ReviewRating: int
{
    case ONE = 1;
    case TWO = 2;
    case THREE = 3;
    case FOUR = 4;
    case FIVE = 5;


    public function label(): string
    {
        return match ($this) {
            self::ONE => 'Tệ',
            self::TWO => 'Không hài lòng',
            self::THREE => 'Trung bình',
            self::FOUR => 'Tốt',
            self::FIVE => 'Tuyệt vời',
        };
    }

    public static function allLabels(): array
    {
        return [
            self::ONE->label(),
            self::TWO->label(),
            self::THREE->label(),
            self::FOUR->label(),
            self::FIVE->label(),
        ];
    }
}
