<?php

declare(strict_types=1);

namespace App\Person\Entity;

use ApiPlatform\Metadata\ApiProperty;
use App\Shared\Model\Trait\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Inwebo\DoctrineEventSourcing\Model\Interface\StateInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity()]
class PersonState implements StateInterface
{
    use TimestampableTrait;

    #[ORM\Id, ORM\GeneratedValue(strategy: 'IDENTITY'), ORM\Column(type: 'integer', unique: true)]
    #[ApiProperty(identifier: true)]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['personState:read'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['personState:read'])]
    private ?string $lastName = null;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'states')]
    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'id', nullable: false)]
    private Person $person;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function setPerson(Person $person): void
    {
        $this->person = $person;
    }
}
