<?php

namespace App\Repository;

use App\Entity\Gado;
use App\Entity\Fazenda;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gado::class);
    }

    public function findAbatidos(): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.vivo = false')
            ->orderBy('g.dataAbate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function totalLeiteSemana(): float
    {
        $total = $this->createQueryBuilder('g')
            ->select('SUM(g.leite)')
            ->getQuery()
            ->getSingleScalarResult();
        return $total * 7;
    }

    public function totalRacaoSemana(): float
    {
        $total = $this->createQueryBuilder('g')
            ->select('SUM(g.racao)')
            ->getQuery()
            ->getSingleScalarResult();
        return $total * 7;
    }

    public function animaisParaAbate(): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.vivo = true')
            ->getQuery()
            ->getResult();
    }

    public function animaisComMenosDeUmAnoMaisDe500kg(): array
    {
        $umAnoAtras = new \DateTime('-1 year');

        return $this->createQueryBuilder('g')
            ->andWhere('g.dataNascimento > :umAno')
            ->setParameter('umAno', $umAnoAtras)
            ->andWhere('(g.racao * 7) > 500')
            ->getQuery()
            ->getResult();
    }

        public function existsCodigoVivo(string $codigo, ?int $exceptId = null): bool
    {
        $qb = $this->createQueryBuilder('g')
            ->select('COUNT(g.id)')
            ->where('g.codigo = :codigo')
            ->andWhere('g.vivo = true')
            ->setParameter('codigo', $codigo);

        if ($exceptId) {
            $qb->andWhere('g.id != :id')->setParameter('id', $exceptId);
        }

        return (int)$qb->getQuery()->getSingleScalarResult() > 0;
    }
}
