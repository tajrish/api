<?php

namespace Tajrish\Helpers;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class Auther
{
    /**
     * @return null
     */
    public static function user()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if($user === null) {
                return null;
            }

        } catch (JWTException $e) {

            return null;
        }


        return $user;
    }
}