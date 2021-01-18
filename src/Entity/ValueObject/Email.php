<?php


namespace App\Entity\ValueObject;


use App\Helpers\Exception\InvalidEmailException;
use App\Helpers\Validator\EmailValidator;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Email
 * @package App\Entity\ValueObject
 * @ORM\Embeddable()
 */
class Email implements JsonSerializable
{
    /**
     * @var string
     * @ORM\Column(type="string", length=255, name="email")
     */
    private string $address;

    /**
     * Email constructor.
     * @param string $address
     */
    public function __construct(string $address)
    {
        $this->setEmail($address);
    }

    /**
     * @param string $address
     * @return $this
     */
    private function setEmail(string $address): self
    {
        if(!EmailValidator::isValid($address)){
            throw new InvalidEmailException(
                "E-mail: $address é inválido",
                Response::HTTP_BAD_REQUEST
            );
        }
        $this->address = $address;
        return $this;
    }

    public function __toString()
    {
        return (string) $this->address;
    }

    public function jsonSerialize()
    {
        return $this->address;
    }
}