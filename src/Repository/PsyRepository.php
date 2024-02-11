<?php

namespace App\Repository;

use App\Entity\Psy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Psy>
 *
 * @method Psy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Psy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Psy[]    findAll()
 * @method Psy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PsyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Psy::class);
    }

    public function save(Psy $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Psy $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
