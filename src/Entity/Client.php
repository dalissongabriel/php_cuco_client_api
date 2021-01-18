<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use App\ValueObject\Cpf;
use App\ValueObject\Email;
use App\ValueObject\Phone;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private Cpf $cpf;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private Email $email;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private Phone $phone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCpf(): ?Cpf
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): self
    {
        $this->cpf = new Cpf($cpf);

        return $this;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = new Email($email);

        return $this;
    }

    public function getPhone(): ?Phone
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = new Phone($phone);

        return $this;
    }

    public function jsonSerialize(): string
    {
        $serializable = [
            "name"=>$this->getName(),
            "cpf"=>$this->getCpf(),
            "email"=>$this->getEmail()
        ];

        if (!is_null($this->getPhone())) {
            array_merge($serializable, ["phone"=>$this->getPhone()]);
        }

        return $serializable;
    }
}
