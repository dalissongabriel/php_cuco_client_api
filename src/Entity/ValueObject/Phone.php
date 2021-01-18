<?php


namespace App\Entity\ValueObject;


use App\Helpers\Exception\InvalidPhoneException;
use App\Helpers\Validator\PhoneValidator;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Class Phone
 * @ORM\Embeddable()
 */
class Phone implements JsonSerializable
{
    /**
     * @ORM\Column(type="string", length=25, nullable=true, name="phone")
     **/
    private string $number;

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