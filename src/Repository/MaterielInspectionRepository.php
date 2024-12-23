<?php

namespace App\Repository;

use App\Entity\MaterielInspection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MaterielInspection>
 */
class MaterielInspectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaterielInspection::class);
    }

    /**
     * @param array<int,mixed> $criterial
     */
    public function findParms(array $criterial): mixed
    {
        
        $query = $this->createQueryBuilder('mi')
            ->select('mi.id, mi.productCol, mi.dateIntsall, mi.dateInspect, mi.status, mi.description')
        ;
        
        if (!empty($criterial['productCol'])) {
            $i = 0;
            foreach (explode(" ", $criterial['productCol']) as $w) {
                $query->andWhere("UPPER(mi.productCol) LIKE UPPER(:productCol{$i})")
                    ->setParameter("productCol{$i}", "%{$w}%");
            }
        }

        if (!empty($criterial['dateIntsallDebut']) && empty($criterial['dateIntsallFin'])) {
            $query->andWhere("mi.dateIntsall > :dateIntsallDebut")
                ->setParameter("dateIntsallDebut", $criterial['dateIntsallDebut']);
        }
        else if (empty($criterial['dateIntsallDebut']) && !empty($criterial['dateIntsallFin'])) {
            $query->andWhere("mi.dateIntsall < :dateIntsallFin")
                ->setParameter("dateIntsallFin", $criterial['dateIntsallFin']);
        }
        else if (!empty($criterial['dateIntsallDebut']) && !empty($criterial['dateIntsallFin'])) {
            $query->andWhere("mi.dateIntsall between :dateIntsallDebut and :dateIntsallFin")
                ->setParameter("dateIntsallDebut", $criterial['dateIntsallDebut'])
                ->setParameter("dateIntsallFin", $criterial['dateIntsallFin']);
        }

        if (!empty($criterial['dateInspectDebut']) && empty($criterial['dateIntsallFin'])) {
            $query->andWhere("mi.dateInspect > :dateInspectDebut")
                ->setParameter("dateInspectDebut", $criterial['dateInspectDebut']);
        }
        else if (empty($criterial['dateInspectDebut']) && !empty($criterial['dateInspectFin'])) {
            $query->andWhere("mi.dateInspect < :dateInspectFin")
                ->setParameter("dateInspectDebut", $criterial['dateInspectFin']);
        }
        else if (!empty($criterial['dateInspectDebut']) && !empty($criterial['dateInspectFin'])) {
            $query->andWhere("mi.dateInspect between :dateInspectDebut and :dateInspectFin")
                ->setParameter("dateInspectDebut", $criterial['dateInspectDebut'])
                ->setParameter("dateInspectFin", $criterial['dateInspectFin']);
        }

        if (!empty($criterial['status'])) {
            $query->andWhere("UPPER(mi.status) LIKE UPPER(:status)")
                ->setParameter("status", $criterial['status']);
        }
        
        return $query->getQuery()->getArrayResult();
    }

    //    /**
    //     * @return MaterielInspection[] Returns an array of MaterielInspection objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MaterielInspection
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
