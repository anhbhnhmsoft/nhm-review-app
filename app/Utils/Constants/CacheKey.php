<?php

namespace App\Utils\Constants;

enum CacheKey: string
{
    case ALL_CONFIG = 'ALL_CONFIG';



    /**
     * Render cache key hoàn chỉnh.
     */
    public function render(string ...$args): string
    {
        if (empty($args)) {
            return $this->value;
        }

        return $this->value . '_' . implode('_', $args);
    }
}
