<?php

namespace App\Repository;

use App\Entity\Psycho;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PsychoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Psycho::class);
    }

    public function findAllUsers(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT DISTINCT p.firstname, p.lastname
             FROM App\Entity\Psycho p'
        );

        return $query->getResult();
    }
}
