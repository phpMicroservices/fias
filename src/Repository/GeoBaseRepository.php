<?php

namespace App\Repository;

use App\Entity\GeoBase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GeoBase|null find($id, $lockMode = null, $lockVersion = null)
 * @method GeoBase|null findOneBy(array $criteria, array $orderBy = null)
 * @method GeoBase[]    findAll()
 * @method GeoBase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GeoBaseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GeoBase::class);
    }
}
