<?php

namespace App\Repository;

use App\Entity\TvShow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Member;

/**
 * @extends ServiceEntityRepository<TvShow>
 */
class TvShowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TvShow::class);
    }


    public function findAll(): array
    {
        //  findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
        return $this->findBy(
                []
        );
    }

    /**
     * @return [TvShow][] Returns an array of [Objet] objects for a member
     */
    public function findMemberTvShow(Member $member): array
    {
            return $this->createQueryBuilder('o')
                    ->leftJoin('o.onlineCatalog', 'i')
                    ->andWhere('i.owner = :member')
                    ->setParameter('member', $member)
                    ->getQuery()
                    ->getResult()
            ;
    }





    //    /**
    //     * @return TvShow[] Returns an array of TvShow objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TvShow
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}