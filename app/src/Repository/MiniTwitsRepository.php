<?php

namespace App\Repository;

use App\Entity\MiniTwits;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Symfony\Component\Validator\Constraints\IsNull;

/**
 * @extends ServiceEntityRepository<MiniTwits>
 *
 * @method MiniTwits|null find($id, $lockMode = null, $lockVersion = null)
 * @method MiniTwits|null findOneBy(array $criteria, array $orderBy = null)
 * @method MiniTwits[]    findAll()
 * @method MiniTwits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MiniTwitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MiniTwits::class);
    }

    public function save(MiniTwits $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MiniTwits $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // public function countComment():array
    // {
    //     $dbQuery = $this->getEntityManager()->
    //     $sql = 'SELECT id, mini_twit_id FROM mini_twits '
    //     return $dbQuery->getQuery()->getResult();
    // }
    
    public function findFollowedTwits(array | Collection $followed)
    {
       return $this->findAllInfo(getAuthor:true, getLikes:true, getProfiles:true, getReplies:true)
       ->where('m.author in (:followed)')
       ->andWhere('m.miniTwit is NULL')
       ->setParameter('followed', $followed)
       ->getQuery()
       ->getResult();
    }

    public function findByAuthor(int | User $user, bool $withReplies = false):array
    {
        $dbQuery = $this->findAllInfo(getAuthor:true, getLikes:true, getProfiles:true, getReplies:true);

        if(!$withReplies){
            $dbQuery->where('m.miniTwit is NULL');
        }

        return $dbQuery->andWhere('m.author = :user')
            ->setParameter('user',$user)
            ->orderBy('m.created', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findLikedByUser(int | User $user):array
    {
        return $this->findAllInfo(getAuthor:true, getLikes:true, getProfiles:true, getReplies:true)
            ->where('l = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function findTopTwits(int $limit = 20):array
    {
        $topLikedList = $this->findAllInfo(getLikes:true)
            ->where('m.miniTwit is NULL')
            ->select('m.id, count(l), m.created')
            ->groupBy('m.id')
            ->orderBy('count(l)','DESC')
            ->addOrderBy('m.created','DESC') 
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult(Query::HYDRATE_SCALAR_COLUMN);
        return $this->findAllInfo(getAuthor:true, getLikes:true, getProfiles:true, getReplies:true)
            ->where('m.id in (:topLikedList)')
            ->setParameter('topLikedList', $topLikedList)
            ->getQuery()
            ->getResult();
        
    }

    public function findAllWithLikes(int | MiniTwits $miniTwitId = null):array
    {   
        $dbQuery = $this->findAllInfo(getAuthor:true, getLikes:true, getProfiles:true, getReplies:true);
        
        if(is_null($miniTwitId)){
            $dbQuery->where('m.miniTwit is NULL');
        }
        else{
            $dbQuery->where('m.miniTwit=:miniTwit')->setParameter('miniTwit',$miniTwitId);
        }
        
        return $dbQuery->getQuery()->getResult();
    }


    private function findAllInfo(bool $getAuthor = false, bool $getLikes = false, bool $getProfiles = false, bool $getReplies = false ):QueryBuilder
    {
        $query = $this->createQueryBuilder('m');
        if ($getAuthor||$getProfiles) {
            $query->leftJoin('m.author','a')
                ->addSelect('a');
        }
        if ($getLikes) {
            $query->leftJoin('m.likedBy','l')
                ->addSelect('l');
        }
        if ($getProfiles) {
            $query->leftJoin('a.userProfile','p')
                ->addSelect('p');
        }
        if($getReplies){
            $query->leftJoin('m.miniTwits','mt')
                ->addSelect('mt');
        }

        return $query->orderBy('m.created','DESC');
    }
//    /**
//     * @return MiniTwits[] Returns an array of MiniTwits objects
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

//    public function findOneBySomeField($value): ?MiniTwits
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
