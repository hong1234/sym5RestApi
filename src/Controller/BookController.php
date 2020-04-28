<?php
// src/Controller/BookController.php
namespace App\Controller;

use App\Entity\Book;
use App\Entity\Review;
use App\Service\BookService;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class BookController
 * @package App\Controller
 *
 * @Route(path="/api/books")
 */
class BookController extends AbstractController
{
    private $bookService;
   
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
        
    }

    /**
     * @Route("/{bookId}/reviews", name="get_book_reviews", methods={"GET"})
     */
    public function bookReviews($bookId): Response
    { 
        $bookReviewsJson = $this->bookService->bookReviews($bookId);
        if ($bookReviewsJson==null) 
            return new Response(json_encode(['error' => 'Book not found']), Response::HTTP_NOT_FOUND);
        return new Response($bookReviewsJson, 200, ['Content-Type' => 'application/json']);
       
    }

    /**
     * @Route("/{bookId}/reviews", name="add_review", methods={"POST"})
     */
    public function addBookReview($bookId, Request $request): Response
    { 
        $data = json_decode($request->getContent(), true);
        $reviewJson = $this->bookService->addReview($bookId, $data);
        if($reviewJson==null)
            return new Response(json_encode(['error' => 'Book not found']), Response::HTTP_NOT_FOUND);
        return new Response($reviewJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/reviews/{reviewId}", name="delete_book_review", methods={"DELETE"})
     */
    public function deleteBookReview($reviewId): Response
    {
        $status = $this->bookService->deleteReview($reviewId);
        if ($status ==null)
            return new Response(json_encode(['error' => 'Review not found']), Response::HTTP_NOT_FOUND);
        return new Response(json_encode(['status' => 'Review Id='.$reviewId.' deleted']), Response::HTTP_OK);
    }

    /////////////////

     /**
     * @Route("/search", name="search_book_title", methods={"GET"})
     */
    public function searchBook(Request $request)
    {  
        $searchkey = $request->query->get('title');
        $booksJson = $this->bookService->searchBookByTitle($searchkey);
        return new Response($booksJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("", name="all_books", methods={"GET"})
     */
    public function getAllBooks(): Response
    {
    	$booksJson = $this->bookService->allBooks();
        return new Response($booksJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{bookId<\d+>}", name="show_book", methods={"GET"})
     */
    public function showBook(int $bookId): Response
    {  
        $bookJson = $this->bookService->getBook($bookId);
        if($bookJson==null)
            return new Response(json_encode(['error' => 'Book not found']), Response::HTTP_NOT_FOUND);
        return new Response($bookJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("", name="create_book", methods={"POST"})
     */
    public function createBook(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $bookJson = $this->bookService->addBook($data);
        return new Response($bookJson, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{bookId}", name="update_book", methods={"PUT"})
     */
    public function updateBook($bookId, Request $request): Response
    {   
        $data = json_decode($request->getContent(), true);
        $updatedBookJson = $this->bookService->updateBook($bookId, $data);
        if ($updatedBookJson==null)
            return new Response(json_encode(['error' => 'Book not found']), Response::HTTP_NOT_FOUND);
        return new Response($updatedBookJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{bookId}", name="delete_book", methods={"DELETE"})
     */
    public function delete($bookId): Response
    {   
        $status = $this->bookService->deleteBook($bookId);
        if($status==null)
            return new Response(json_encode(['error' => 'Book not found']), Response::HTTP_NOT_FOUND);
        return new Response(json_encode(['status' => 'Book deleted']), Response::HTTP_OK);
    }

}

