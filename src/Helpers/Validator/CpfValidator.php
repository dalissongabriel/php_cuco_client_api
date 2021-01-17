<?php


namespace App\Helpers\Validator;


trait CpfValidator
{
    public static function isValid($cpf): bool
    {

        if (!CpfValidator::isCpf($cpf)) {
            return false;
        }

        $numberCpf = CpfValidator::removeFormatting($cpf);

        if (!CpfValidator::checkEqualNumbers($numberCpf)) {
            return false;
        }

        if (!CpfValidator::checkDigits($numberCpf)) {
            return false;
        }

        return true;
    }

    private static function isCpf($cpf): bool
    {
        $regex_cpf = "/[0-9]{3}\.[0-9]{3}\.[0-9]{3}\-[0-9]{2}/";
        return preg_match($regex_cpf, $cpf);
    }

    private static function removeFormatting(string $cpf): string
    {
        return str_replace([".", "-"], "", $cpf);
    }

    private static function checkEqualNumbers($cpf): bool
    {
        for ($i = 0; $i <= 11; $i++) {
            if (str_repeat($i, 11) == $cpf) return false;
        }
        return true;
    }


    private static function checkDigits($cpf): bool
    {
        $firstNumber = 0;
        $secondNumber = 0;

        for ($i = 0, $weight = 10; $i <= 8; $i++, $weight--) {
            $firstNumber += $cpf[$i] * $weight;
        }

        for ($i = 0, $weight = 11; $i <= 9; $i++, $weight--) {
            $secondNumber += $cpf[$i] * $weight;
        }

        $firstCalculation  = (($firstNumber % 11) < 2) ? 0 : 11 - ($firstNumber % 11);
        $secondCalculation = (($secondNumber % 11) < 2) ? 0 : 11 - ($secondNumber % 11);

        if ($firstCalculation <> $cpf[9] || $secondCalculation <> $cpf[10]) {
            return false;
        }

        return true;
    }

}