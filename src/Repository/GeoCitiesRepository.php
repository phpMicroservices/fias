<?php

namespace App\Repository;

use App\Entity\GeoCities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GeoCities|null find($id, $lockMode = null, $lockVersion = null)
 * @method GeoCities|null findOneBy(array $criteria, array $orderBy = null)
 * @method GeoCities[]    findAll()
 * @method GeoCities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GeoCitiesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GeoCities::class);
    }
}
