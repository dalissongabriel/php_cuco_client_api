<?php


namespace App\Helpers\Validator;


trait PhoneValidator
{
    public static function isValid(string $phone): bool
    {
        $regex_phone = "/\([0-9]{2}\) [0-9]{5}[0-9]{4}/";
        return preg_match($regex_phone, $phone);
    }
}