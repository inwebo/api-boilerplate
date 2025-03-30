<?php

namespace App\Person\Entity;

use ApiPlatform\Metadata\ApiProperty;
use App\Person\Event\listener\StoreListener;
use App\Person\Repository\PersonRepository;
use App\Shared\Model\Trait\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Inwebo\DoctrineEventSourcing\Mapping\AggregateRoot;
use Inwebo\DoctrineEventSourcing\Mapping\AggregateSource;
use Inwebo\DoctrineEventSourcing\Model\Interface\HasStatesInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[ORM\EntityListeners([StoreListener::class])]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: false)]
#[AggregateRoot(stateClass: PersonState::class, subjectSetter: 'setPerson')]
class Person implements HasStatesInterface
{
    use TimestampableTrait;

    #[ORM\Id, ORM\GeneratedValue(strategy: 'IDENTITY'), ORM\Column(type: 'integer', unique: true)]
    #[ApiProperty(identifier: true)]
    #[Groups(['person:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['person:read', 'person:write'])]
    #[AggregateSource(getter: 'getFirstName', setter: 'setFirstName')]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['person:read', 'person:write'])]
    #[AggregateSource(getter: 'getLastName', setter: 'setLastName')]
    private ?string $lastName = null;

    #[ORM\OneToMany(targetEntity: PersonState::class, mappedBy: 'person', cascade: ['persist'])]
    #[Groups(['person:read'])]
    private Collection $states;

    public function getEventSourcingStates(): Collection
    {
        return $this->states;
    }

    public function __construct()
    {
        $this->states = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }
}
