<?php

namespace App\Utils\Constants;

enum StoreStatus: int
{
    case ACTIVE = 1;
    case INACTIVE = 2;
    case PENDING = 3;

}
