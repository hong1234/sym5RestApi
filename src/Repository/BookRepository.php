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
        $book->setTitle($data['title']);
        $book->setContent($data['content']);
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

}
