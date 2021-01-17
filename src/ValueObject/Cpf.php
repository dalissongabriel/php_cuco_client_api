<?php


namespace App\ValueObject;


use App\Helpers\Exception\InvalidCpfException;
use App\Helpers\Validator\CpfValidator;
use Symfony\Component\HttpFoundation\Response;

class Cpf
{
    private string $number;

    /**
     * Cpf constructor.
     * @param string $number
     */
    public function __construct(string $number)
    {
        $this->setCpf($number);
    }

    private function setCpf(string $number): self
    {
        if( !CpfValidator::isValid($number) ) {
            throw new InvalidCpfException(
                "Cpf $number é inválido",
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->number = $number;
        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->number;
    }
}