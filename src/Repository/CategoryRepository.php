<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    const DEFAULT_ALIAS = 'category';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder(self::DEFAULT_ALIAS);
    }

    public function findBySlugs(array $slugs): array
    {
        $qb = $this->getRepository()->getQueryBuilder();

        $qb->andWhere($qb->expr()->in(sprintf('%s.slug', self::DEFAULT_ALIAS), $slugs));

        return $qb->getQuery()->getResult();
    }
}
