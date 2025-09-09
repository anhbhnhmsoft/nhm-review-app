<?php

namespace App\Utils\Constants;

enum LoginResult: string
{
    case SUCCESS = 'success';
    case INVALID_CREDENTIALS = 'invalid_credentials';
    case UNVERIFIED_EMAIL = 'unverified';
}
