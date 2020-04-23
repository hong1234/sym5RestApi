<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class BookRepository extends ServiceEntityRepository
{ 
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Book::class);
        $this->manager = $manager;
    }

    public function saveBook($title, $content)
    {
        $book = new Book();
        $book->setTitle($title)
             ->setContent($content)
             ->setCreatedOn(date_format(new \DateTime(),'d-m-Y'))
             ->setUpdatedOn(date_format(new \DateTime(),'d-m-Y'));

        $this->manager->persist($book);
        $this->manager->flush();  
        return $book;    
    }

    public function updateBook(Book $book, $data)
    {
        empty($data['title']) ? true : $book->setTitle($data['title']);
        empty($data['content']) ? true : $book->setContent($data['content']);
        $book->setUpdatedOn(date_format(new \DateTime(),'d-m-Y'));

        $this->manager->flush();
        
    }

    public function removeBook(Book $book)
    {
        $this->manager->remove($book);
        $this->manager->flush();
    }

    public function searchBook(String $searchkey)
    {
        return $this->createQueryBuilder('b')
        ->where('b.title LIKE :searchkey')
        ->setParameter('searchkey', '%'.$searchkey.'%')
        ->orderBy('b.id', 'ASC')
        ->getQuery()
        ->getResult();      
    }

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
