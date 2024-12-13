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

    public function findParms(array $criterial): mixed
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(MaterielInspection::class, 'mi');

        $where = [];
        $params = [];

        if (!empty($criterial['productCol'])) {
            foreach (explode(" ", $criterial['productCol']) as $w) {
                $params['productCol'] = "%$w%";
                $where[] = "UPPER(mi.productCol) ilike UPPER(:productCol)";
            }
        }

        if (!empty($criterial['dateIntsallDebut']) && empty($criterial['dateIntsallFin'])) {
            $params['dateIntsallDebut'] = $criterial['dateIntsallDebut'];
            $where[] = "mi.dateIntsall > :dateIntsallDebut";
        }
        else if (empty($criterial['dateIntsallDebut']) && !empty($criterial['dateIntsallFin'])) {
            $params['dateIntsallFin'] = $criterial['dateIntsallFin'];
            $where[] = "mi.dateIntsall < :dateIntsallFin";
        }
        else if (!empty($criterial['dateIntsallDebut']) && !empty($criterial['dateIntsallFin'])) {
            $params['dateIntsallDebut'] = $criterial['dateIntsallDebut'];
            $params['dateIntsallFin'] = $criterial['dateIntsallFin'];
            $where[] = "mi.dateIntsall between :dateIntsallDebut and :dateIntsallFin";
        }




        $sql = "SELECT {$rsm->generateSelectClause(['mi' => 'mi'])}
        From MaterielInspection mi ";

        $res = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        return $res->getResult();
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
