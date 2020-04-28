<?php

namespace App\Service;

use App\Repository\BookRepository;
use App\Repository\ReviewRepository;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class BookService
{
    private $bookRepository;
    private $reviewRepository;

    public function __construct(BookRepository $bookRepository, ReviewRepository $reviewRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->reviewRepository = $reviewRepository;
    }

    public function bookReviews($bookId)
    { 
        $book = $this->bookRepository->findOneBy(['id' => $bookId]);
        if (!$book)
            return null; 
        return $this->toJson($book->getReviews());
    }

    public function addReview($bookId, $data)
    { 
        $book = $this->bookRepository->findOneBy(['id' => $bookId]);
        if (!$book) 
            return null;
            
        $name = $data['name'];
        $email = $data['email'];
        $content = $data['content'];
        $savedReview = $this->reviewRepository->saveReview($book, $name, $email, $content);
        return $this->toJson($savedReview);
    }

    public function deleteReview($reviewId)
    {
        $review = $this->reviewRepository->findOneBy(['id' => $reviewId]);
        if (!$review)
            return null;
            
        $this->reviewRepository->removeReview($review);
        return 1;
    }

    ///////////

    public function searchBookByTitle($searchkey)
    {  
        //$searchkey = $request->query->get('title');
	    $books = $this->bookRepository->searchBook($searchkey);
        return $this->toJson($books);
        //return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
        
    }

    public function allBooks()
    {
    	$books = $this->bookRepository->findAll();
        return $this->toJson($books);
        //return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function getBook(int $bookId)
    {  
        $book = $this->bookRepository->findOneBy(['id' => $bookId]);
        if (!$book)
            return null;
    	
        return $this->toJson($book);
        
    }

    public function addBook($data)
    {
        $title = $data['title'];
        $content = $data['content'];

        $book = $this->bookRepository->saveBook($title, $content);
        return $this->toJson($book);
    }

    public function updateBook($bookId, $data)
    {    
        $book = $this->bookRepository->findOneBy(['id' => $bookId]);
        if (!$book)
            return null;

        $this->bookRepository->updateBook($book, $data);
        return $this->toJson($book);
    }

    public function toJson($items)
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        return $serializer->serialize($items, 'json', [
		    'circular_reference_handler' => function ($object) { return $object->getId(); },
            'ignored_attributes' => ['book']
	    ]);
    }

    public function deleteBook($bookId)
    {   
        $book = $this->bookRepository->findOneBy(['id' => $bookId]);
        if (!$book)
            return null;
        $this->bookRepository->removeBook($book);
        return 1;
    }

}