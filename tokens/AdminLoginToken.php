<?php

namespace yz\admin\tokens;
use omnilight\tokens\algorithms\RandomString;
use omnilight\tokens\Token;


/**
 * Class AdminLoginToken
 */
class AdminLoginToken extends Token
{
    const TYPE = 'adminLoginToken';

    public static function createToken($id)
    {
        return self::create(self::TYPE, $id, new RandomString());
    }

    public static function compareToken($id, $token)
    {
        return self::compare(self::TYPE, $id, $token);
    }
}