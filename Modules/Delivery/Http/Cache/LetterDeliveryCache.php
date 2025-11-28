<?php

namespace Modules\Delivery\Http\Cache;

class LetterDeliveryCache
{
    public const BASE = 'LETTER_DELIVERY';

    public const GET = self::BASE . '_GET';

    public const GET_EXPIRY = 60 * 30;

    public const GET_ALL = self::BASE . '_GET_ALL';

    public const GET_ALL_EXPIRY = 60 * 30;
}
