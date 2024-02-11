<?php

namespace App\Repository;

use App\Entity\ProfilposteResultat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProfilposteResultat>
 *
 * @method ProfilposteResultat|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfilposteResultat|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfilposteResultat[]    findAll()
 * @method ProfilposteResultat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfilposteResultatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfilposteResultat::class);
    }

    public function save(ProfilposteResultat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProfilposteResultat $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ProfilposteResultat[] Returns an array of ProfilposteResultat objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProfilposteResultat
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
