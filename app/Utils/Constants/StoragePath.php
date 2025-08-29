<?php

namespace App\Utils\Constants;

enum StoragePath:string
{
    case BANNER_PATH = "banner_images";
    case CATEGORY_PATH = "category_images";
    case STORE_PATH = "stores";


    public static function makePath(StoragePath $type, string $filename): string
    {
        return $type->value . DIRECTORY_SEPARATOR . $filename;
    }

    public static function makePathById(StoragePath $type, string $id): string
    {
        return $type->value . DIRECTORY_SEPARATOR . $id;
    }

}
