<?php


namespace App\ValueObject;


use App\Helpers\Exception\InvalidPhoneException;
use App\Helpers\Validator\PhoneValidator;
use JsonSerializable;

class Phone implements JsonSerializable
{
    private string $number;

    /**
     * Phone constructor.
     * @param string $number
     */
    public function __construct(string $number)
    {
        $this->setPhone($number);
    }

    /**
     * @param string $phone
     * @return $this
     */
    private function setPhone(string $phone): self
    {
        if(!PhoneValidator::isValid($phone)){
            throw new InvalidPhoneException(
                "Telefone: $phone é inválido. Siga este formato: (xx) xxxxxxxxx"
            );
        }

        $this->number = $phone;
        return $this;
    }


    public function __toString(): string
    {
        return $this->number;
    }

    public function jsonSerialize()
    {
        return $this->number;
    }
}