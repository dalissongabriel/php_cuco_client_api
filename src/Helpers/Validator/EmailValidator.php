<?php


namespace App\Helpers\Validator;


trait EmailValidator
{
    public static function isValid($address): bool
    {
        if (filter_var($address, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }
}