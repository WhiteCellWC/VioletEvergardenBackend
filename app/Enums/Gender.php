<?php

namespace App\Enums;

use App\Constant\Constant;

enum Gender: string
{
    case MALE = Constant::male;

    case FEMALE = Constant::female;

    case OTHER = Constant::other;
}
