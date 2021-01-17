<?php


namespace App\ValueObject;


use App\Helpers\Exception\InvalidEmailException;
use App\Helpers\Validator\EmailValidator;
use Symfony\Component\HttpFoundation\Response;

class Email
{
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

}