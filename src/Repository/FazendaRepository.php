<?php

namespace App\Repository;

use App\Entity\Fazenda;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FazendaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fazenda::class);
    }

    /**
     * Verifica se já existe fazenda com o mesmo nome (exceto id opcional).
     */
    public function existsByNome(string $nome, ?int $exceptId = null): bool
    {
        $qb = $this->createQueryBuilder('f')
            ->select('COUNT(f.id)')
            ->andWhere('LOWER(f.nome) = :nome')
            ->setParameter('nome', mb_strtolower($nome));

        if ($exceptId) {
            $qb->andWhere('f.id != :id')->setParameter('id', $exceptId);
        }

        return (int) $qb->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * Conta quantos gados (todos) pertencem à fazenda.
     */
    public function countGados(Fazenda $fazenda): int
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT COUNT(*) FROM gados WHERE fazenda_id = :id';
        return (int) $conn->fetchOne($sql, ['id' => $fazenda->getId()]);
    }

    /**
     * Busca fazenda por nome (like)
     */
    public function findByNomeLike(string $nome): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('LOWER(f.nome) LIKE :nome')
            ->setParameter('nome', '%' . mb_strtolower($nome) . '%')
            ->getQuery()
            ->getResult();
    }
}
