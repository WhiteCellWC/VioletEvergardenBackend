<?php

namespace Modules\Letter\Http\Cache;

class LetterCache
{
    public const BASE = 'LETTER';

    public const GET = 'GET';

    public const GET_EXPIRY = 60 * 30;

    public const GET_ALL = 'GET_ALL';

    public const GET_ALL_EXPIRY = 60 * 30;

    public static function getCacheKey(string $type, mixed ...$params): string {
       $key = self::BASE . '_' . $type;
       if ($params) {
           $key .= ':' . implode('_', $params);
       }
       return $key;
   }
}
