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

    public function saveReview(Book $book, String $name, String $email, String $content)
    {
        $review = new Review();
        $review->setName($name)
               ->setEmail($email)
               ->setContent($content)
               ->setCreatedOn(date_format(new \DateTime(),'d-m-Y'))
               ->setUpdatedOn(date_format(new \DateTime(),'d-m-Y'))
               ->setBook($book);
        $this->manager->persist($review);
        $this->manager->flush(); 
        return $review;
    }

    public function updateReview(Review $review, $data)
    {
        $review->setName($data['name']);
        $review->setEmail($data['email']);
        $review->setContent($data['content']);
        $review->setUpdatedOn(date_format(new \DateTime(),'d-m-Y'));
        $this->manager->flush();
    }

    public function removeReview(Review $review)
    {
        $this->manager->remove($review);
        $this->manager->flush();
    }

}
