<?php declare(strict_types=1);

namespace App\User\Entity;

use ApiPlatform\Metadata\ApiProperty;
use App\Shared\Model\Doctrine\Entity\AbstractEntity;
use App\User\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`inwebo__user`')]
class User extends AbstractEntity
{
    #[ORM\Id, ORM\GeneratedValue(strategy: 'IDENTITY'), ORM\Column]
    #[ApiProperty(identifier: false)]
    private ?int $id = null;

    #[ApiProperty(identifier: true)]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $uuid;

    public function __construct()
    {
        parent::__construct();
    }
}
