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

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`inwebo__user`')]
class User extends AbstractEntity implements UserInterface
{
    #[ORM\Id, ORM\GeneratedValue(strategy: 'IDENTITY'), ORM\Column]
    #[ApiProperty(identifier: false)]
    private ?int $id = null;

    #[ApiProperty(identifier: true)]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
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

    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

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

    public function __construct()
    {
        parent::__construct();
    }
}
