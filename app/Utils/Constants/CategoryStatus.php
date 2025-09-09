<?php

namespace App\Utils\Constants;

enum CategoryStatus: int
{
    case ACTIVE = 1;
    case INACTIVE = 2;

    public static function getOptions(): array
    {
        return [
            self::ACTIVE->value => 'Hoạt động',
            self::INACTIVE->value => 'Không hoạt động',
        ];
    }

    public function getLabel(CategoryStatus $state): array
    {
        return self::getOptions()[$state->value];
    }
}
