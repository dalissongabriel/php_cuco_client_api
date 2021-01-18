<?php


namespace App\Entity\ValueObject;


use App\Helpers\Exception\InvalidCpfException;
use App\Helpers\Validator\CpfValidator;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\HttpFoundation\Response;

/** @ORM\Embeddable() */
class Cpf implements JsonSerializable
{
    /**
     * @ORM\Column(type="string", length=25,name="cpf")
     */
    private string $number;

    /**
     * Cpf constructor.doctrine.yaml
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



    public function jsonSerialize()
    {
        return $this->number;
    }
}