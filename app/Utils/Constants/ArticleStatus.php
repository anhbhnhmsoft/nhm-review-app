<?php

namespace App\Utils\Constants;

enum ArticleStatus: int
{
    case DRAFT = 1;
    case PUBLISHED = 2;

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Nháp',
            self::PUBLISHED => 'Hiển thị',
        };
    }

    public static function getOptions(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        return $options;
    }

}
