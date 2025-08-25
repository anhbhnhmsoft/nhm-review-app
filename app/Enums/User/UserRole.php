<?php

namespace App\Enums\User;

enum UserRole: int
{
    case USER = 0;
    case ADMIN = 1;

    public function label(): string
    {
        return match($this) {
            self::USER => 'User',
            self::ADMIN => 'Admin',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::USER => 'Người dùng',
            self::ADMIN => 'Quản trị viên',
        };
    }
}