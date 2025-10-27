<?php

namespace App\Repository;

use App\Entity\Veterinario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Veterinario>
 */
class VeterinarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Veterinario::class);
    }


    public function save(Veterinario $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Veterinario $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findByCrmv(string $crmv): ?Veterinario
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.crmv = :crmv')
            ->setParameter('crmv', $crmv)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByNome(string $nome): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.nome LIKE :nome')
            ->setParameter('nome', '%' . $nome . '%')
            ->getQuery()
            ->getResult();
    }

    public function existsByCrmv(string $crmv): bool
    {
        $result = $this->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->andWhere('v.crmv = :crmv')
            ->setParameter('crmv', $crmv)
            ->getQuery()
            ->getSingleScalarResult();

        return $result > 0;
    }

    



    //    /**
    //     * @return Veterinario[] Returns an array of Veterinario objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Veterinario
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
