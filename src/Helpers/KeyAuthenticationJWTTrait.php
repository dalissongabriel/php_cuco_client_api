<?php


namespace App\Helpers;


trait KeyAuthenticationJWTTrait
{
    public static function getKey(): string
    {
        return '$argon2i$v=19$m=65536,t=4,p=1$d1cub3hiMlVPbG5lUlR3Qg$/C+R1qfuFeDXXlUw1005fT0ev3Ok4fF1UUzDZXIEgwk';
    }
}