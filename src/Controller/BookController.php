<?php
// src/Controller/BookController.php
namespace App\Controller;

use App\Entity\Book;
use App\Entity\Review;
use App\Repository\BookRepository;
use App\Repository\ReviewRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class BookController
 * @package App\Controller
 *
 * @Route(path="/api/books")
 */
class BookController extends AbstractController
{
    private $bookRepository;
    private $reviewRepository;

    public function __construct(BookRepository $bookRepository, ReviewRepository $reviewRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * @Route("/{bookId}/reviews", name="add_review", methods={"POST"})
     */
    public function addBookReview($bookId, Request $request): Response
    { 
        $book = $this->bookRepository->findOneBy(['id' => $bookId]);
        if (!$book) 
            return new Response(json_encode(['error' => 'Book not found']), Response::HTTP_NOT_FOUND);

        $data = json_decode($request->getContent(), true);
        $name = $data['name'];
        $email = $data['email'];
        $content = $data['content'];
        
        if (empty($name) || empty($email) || empty($content))
            throw new NotFoundHttpException('Expecting mandatory parameters!');

        $savedReview = $this->reviewRepository->saveReview($book, $name, $email, $content);
        $data = $this->toJson($savedReview);
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{bookId}/reviews", name="get_book_reviews", methods={"GET"})
     */
    public function getBookReviews($bookId): Response
    { 
        $book = $this->bookRepository->findOneBy(['id' => $bookId]);
        if (!$book) 
            return new Response(json_encode(['error' => 'Book not found']), Response::HTTP_NOT_FOUND);
    	
        $data = $this->toJson($book->getReviews());
        return new Response($data, 200, ['Content-Type' => 'application/json']);
       
    }

    /**
     * @Route("/reviews/{reviewId}", name="delete_book_review", methods={"DELETE"})
     */
    public function deleteBookReview($reviewId): Response
    {
        $review = $this->reviewRepository->findOneBy(['id' => $reviewId]);
        if (!$review)
            return new Response(json_encode(['error' => 'Review not found']), Response::HTTP_NOT_FOUND);
        $this->reviewRepository->removeReview($review);
        return new Response(json_encode(['status' => 'Review Id='.$reviewId.' deleted']), Response::HTTP_OK);
    }

    /**
     * @Route("", name="create_book", methods={"POST"})
     */
    public function createBook(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $title = $data['title'];
        $content = $data['content'];
        if (empty($title) || empty($content))
            throw new NotFoundHttpException('Expecting mandatory parameters!');

        $book = $this->bookRepository->saveBook($title, $content);
        $data = $this->toJson($book);
        return new Response($data, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{bookId}", name="update_book", methods={"PUT"})
     */
    public function updateBook($bookId, Request $request): Response
    {    
        $book = $this->bookRepository->findOneBy(['id' => $bookId]);
        if (!$book)
            return new Response(json_encode(['error' => 'Book not found']), Response::HTTP_NOT_FOUND);

        $data = json_decode($request->getContent(), true);
        $this->bookRepository->updateBook($book, $data);

        $data = $this->toJson($book);
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("", name="all_books", methods={"GET"})
     */
    public function getAllBooks(): Response
    {
    	$books = $this->bookRepository->findAll();
        $data = $this->toJson($books);
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{bookId<\d+>}", name="show_book", methods={"GET"})
     */
    public function showBook(int $bookId): Response
    {  
        $book = $this->bookRepository->findOneBy(['id' => $bookId]);
	    if (!$book)
            return new Response(json_encode(['error' => 'Book not found']), Response::HTTP_NOT_FOUND);
    	
        $data = $this->toJson($book);
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/search", name="search_book_title", methods={"GET"})
     */
    public function searchBookByTitle(Request $request)
    {  
        $searchkey = $request->query->get('title');
	    $books = $this->bookRepository->searchBook($searchkey);
        $data = $this->toJson($books);
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
        
    }

    /**
     * @Route("/{bookId}", name="delete_book", methods={"DELETE"})
     */
    public function delete($bookId): Response
    {   
        $book = $this->bookRepository->findOneBy(['id' => $bookId]);
        if (!$book)
            return new Response(json_encode(['error' => 'Book not found']), Response::HTTP_NOT_FOUND);
        $this->bookRepository->removeBook($book);
        return new Response(json_encode(['status' => 'Book deleted']), Response::HTTP_OK);
    }

    public function toJson($items)
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        return $serializer->serialize($items, 'json', [
		    'circular_reference_handler' => function ($object) { return $object->getId(); },
            'ignored_attributes' => ['book']
	    ]);
    }

}

