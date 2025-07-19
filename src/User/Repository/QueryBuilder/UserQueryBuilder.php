<?php declare(strict_types=1);

namespace App\User\Repository\QueryBuilder;

use App\Shared\Model\Doctrine\QueryBuilder\AbstractQueryBuilder;

class UserQueryBuilder extends AbstractQueryBuilder
{
    public const string ALIAS = 'user';
}
