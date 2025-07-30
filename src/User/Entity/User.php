<?php declare(strict_types=1);

namespace App\User\Entity;

use ApiPlatform\Metadata\ApiProperty;
use App\Shared\Model\Doctrine\Entity\AbstractEntity;
use App\User\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`inwebo__user`')]
#[UniqueEntity('email')]
class User extends AbstractEntity implements UserInterface
{
    #[ORM\Id, ORM\GeneratedValue(strategy: 'IDENTITY'), ORM\Column]
    #[ApiProperty(identifier: false)]
    private ?int $id = null;

    #[ApiProperty(identifier: true)]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['user:basic'])]
    private Uuid $uuid;

    #[Assert\Email]
    #[ORM\Column(length: 128, unique: true)]
    #[Groups(['user:basic'])]
    private string $email = '';

    /**
     * @var array<int, string>
     */
    #[ORM\Column]
    #[Groups(['user:extended'])]
    private array $roles = [];

    #[ORM\Column(nullable: false)]
    #[Assert\NotBlank]
    #[Assert\PasswordStrength([
        'minScore' => Assert\PasswordStrength::STRENGTH_MEDIUM
    ])]
    private string $password;

    private ?string $plainPassword = null;

    public function getUserIdentifier(): string
    {
        return (string) $this->uuid;
    }

    /**
     * @return array<int, string>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function addRole(string $role): static
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @param array<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function __construct()
    {
        parent::__construct();
    }
}
