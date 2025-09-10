<?php

namespace App\Utils\Constants;

enum ArticleType: int
{
    case FIXED = 99; // cố định, ko dc tạo thêm

    case PRESS = 1;

    case NEWS = 2;

    case HANDBOOK = 3;

    case PROMOTION = 4;


    public function label(): string
    {
        return match ($this) {
            self::FIXED => 'Cố định',
            self::PRESS => 'Báo chí',
            self::NEWS => 'Tin tức',
            self::HANDBOOK => 'Cẩm nang',
            self::PROMOTION => 'Khuyến mãi hot',
        };
    }

    public static function getOptions(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            if ($case->value !== self::FIXED->value) {
                $options[$case->value] = $case->label();
            }
        }
        return $options;
    }

}
