<?php

namespace Tajrish\Services\Tosan\Helpers;

use Illuminate\Support\Str;

class TokenGenerator
{
    /**
     * Generate a new token
     *
     * @return string
     */
    public static function generate()
    {
        $time = uniqid('', true);
        $rand = Str::random(16);
        return $time.'_'.$rand;
    }
}