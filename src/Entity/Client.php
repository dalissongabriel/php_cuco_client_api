<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use App\Entity\ValueObject\Cpf;
use App\Entity\ValueObject\Email;
use App\Entity\ValueObject\Phone;
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
     * @ORM\Embedded(class="App\Entity\ValueObject\Cpf", columnPrefix = false)
     */
    private Cpf $cpf;

    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\Email", columnPrefix = false)
     */
    private Email $email;

    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\Phone", columnPrefix = false)
     */
    private ?Phone $phone = null;

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

    public function jsonSerialize()
    {
        $data = [
            "id"=>$this->getId(),
            "name"=>$this->getName(),
            "cpf"=>$this->getCpf(),
            "email"=>$this->getEmail()
        ];

        if (!is_null($this->getPhone())) {
            $data = array_merge($data, ["phone"=>$this->getPhone()]);
        }

        return $data;
    }
}
