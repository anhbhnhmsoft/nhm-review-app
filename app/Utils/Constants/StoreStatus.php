<?php

namespace App\Utils\Constants;

enum StoreStatus: int
{
    case ACTIVE = 1;
    case INACTIVE = 2;
    case PENDING = 3;

    public static function getOptions(): array
    {
        return [
            self::ACTIVE->value => 'Hoạt động',
            self::INACTIVE->value => 'Ẩn',
            self::PENDING->value => 'Tạm dừng hoạt động',
        ];
    }
    public static function getLabel(int $value): ?string
    {
        return self::getOptions()[$value] ?? "Không có";
    }
}
