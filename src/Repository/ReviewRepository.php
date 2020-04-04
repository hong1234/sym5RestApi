<?php

namespace App\Repository;

use App\Entity\Review;
use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class ReviewRepository extends ServiceEntityRepository
{ 
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Review::class);
        $this->manager = $manager;
    }

    public function saveReview(Book $book, String $name, String $description)
    {
        $review = new Review();
        $review->setName($name)
               ->setDescription($description)
               ->setInserteddate(date_format(new \DateTime(),'d-m-Y'))
               ->setUpdateddate(date_format(new \DateTime(),'d-m-Y'))
               ->setBook($book);
        $this->manager->persist($review);
        $this->manager->flush(); 
        return $review;
    }

    public function updateReview(Review $review, $data)
    {
        empty($data['name']) ? true : $review->setName($data['name']);
        empty($data['description']) ? true : $review->setDescription($data['description']);
        $review->setUpdateddate(date_format(new \DateTime(),'d-m-Y'));
        $this->manager->flush();
    }

    public function removeReview(Review $review)
    {
        $this->manager->remove($review);
        $this->manager->flush();
    }

    //public function searchReview(String $searchkey)
    //{
    //    return $this->createQueryBuilder('r')
    //    ->where('r.description LIKE :searchkey')
    //    ->setParameter('searchkey', '%'.$searchkey.'%')
    //    ->orderBy('r.id', 'ASC')
    //    ->getQuery()
    //    ->getResult();      
    //}

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param $price
     * @return Book[]
     */
    public function findAllLessThanPrice($price): array
    {
        // automatically knows to select Books
        // the "b" is an alias you'll use in the rest of the query
        $qb = $this->createQueryBuilder('b')
            ->andWhere('b.price < :price')
            ->setParameter('price', $price)
            ->orderBy('b.price', 'ASC')
            ->getQuery();

        return $qb->execute();

        // to get just one result:
        // $product = $qb->setMaxResults(1)->getOneOrNullResult();
    }
}
