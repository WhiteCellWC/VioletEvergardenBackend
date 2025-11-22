<?php

use App\Constant\Constant;
use App\Enums\FlagType;
use Inertia\Inertia;

if (!function_exists('returnView')) {
    function returnView($path, $props)
    {
        return Inertia::render($path, $props);
    }
}

if (! function_exists('redirectView')) {
    function redirectView($routeName = null, $msg = null, FlagType $flag = FlagType::SUCCESS, $parameter = null)
    {
        $status = [
            Constant::flag => $flag->value,  // store enum value in session
            Constant::msg  => $msg,
        ];

        if (empty($parameter) && ! empty($routeName)) {
            return redirect()->route($routeName)->with(Constant::status, $status);
        } elseif (empty($routeName) && empty($parameter)) {
            return redirect()->back()->with(Constant::status, $status);
        } else {
            return redirect()->route($routeName, $parameter)->with(Constant::status, $status);
        }
    }
}

if (! function_exists('getImageUrls')) {
    function getImageUrls($class)
    {
        $images = [];
        if (empty($class->images)) {
            return $images;
        }

        foreach($class->images as $image) {
            $images[] = env('APP_URL') . $image->image_path;
        }

        return $images;
    }
}
