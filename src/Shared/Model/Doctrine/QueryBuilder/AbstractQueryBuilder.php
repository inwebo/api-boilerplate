<?php declare(strict_types=1);

namespace App\Shared\Model\Doctrine\QueryBuilder;

use Doctrine\ORM\QueryBuilder;

class AbstractQueryBuilder extends QueryBuilder
{
    public const ALIAS = '@to_override';
}
