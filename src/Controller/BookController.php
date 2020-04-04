<?php
// src/Controller/BookController.php
namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Entity\Review;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class BookController
 * @package App\Controller
 *
 * @Route(path="/books")
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
    public function addBookReview($bookId, Request $request): JsonResponse
    { 
        $book = $this->bookRepository->findOneBy(['id' => $bookId]);

        $data = json_decode($request->getContent(), true);
        $name = $data['name'];
        $description = $data['description'];
        
        if (empty($name) || empty($description)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $savedReview = $this->reviewRepository->saveReview($book, $name, $description);
        
        return new JsonResponse(['status' => 'Review Id='.$savedReview->getId().' added!'], Response::HTTP_OK);
    }

    /**
     * @Route("/{bookId}/reviews", name="get_book_reviews", methods={"GET"})
     */
    public function getBookReviews($bookId): Response
    { 
        $book = $this->bookRepository->findOneBy(['id' => $bookId]);

        if (!$book) 
            throw $this->createNotFoundException('No book found for id '.$id);
    	
        $data = $this->toJson($book->getReviews());
        return new Response($data, 200, ['Content-Type' => 'application/json']);
       
    }

    /**
     * @Route("/{bookId}/reviews/{reviewId}", name="delete_book_review", methods={"DELETE"})
     */
    public function deleteBookReview($bookId, $reviewId): JsonResponse
    {
        $review = $this->reviewRepository->findOneBy(['id' => $reviewId]);
        $this->reviewRepository->removeReview($review);

        return new JsonResponse(['status' => 'Review Id='.$reviewId.' Of Book Id='.$bookId.' deleted'], Response::HTTP_NO_CONTENT);
    }


    /**
     * @Route("", name="add_book", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];
        $price = $data['price'];
        $description = $data['description'];
        
        if (empty($name) || empty($price) || empty($description)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->bookRepository->saveBook($name, $price, $description);

        return new JsonResponse(['status' => 'Book created!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="update_book", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        
        $book = $this->bookRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);
        $this->bookRepository->updateBook($book, $data);

        return new JsonResponse(['status' => 'Book updated!'], Response::HTTP_OK);
    }

    /**
     * @Route("", name="all_books", methods={"GET"})
     */
    public function getAll(): Response
    {
    	$books = $this->bookRepository->findAll();
        $data = $this->toJson($books);
        //return new JsonResponse($data, Response::HTTP_OK);
        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/{id}", name="show_book", methods={"GET"})
     */
    public function show($id)
    {  
        $book = $this->bookRepository->findOneBy(['id' => $id]);
	if (!$book) 
            throw $this->createNotFoundException('No book found for id '.$id);
    	
        $data = $this->toJson($book);
        return new Response($data, 200, ['Content-Type' => 'application/json']);
        //return new JsonResponse($data, Response::HTTP_OK);
    	// return $this->json(['id'=>$book->getId(), 'name'=>$book->getName()]);

    }

    /**
     * @Route("/search/byname", name="search_book_byname", methods={"GET"})
     */
    public function search(Request $request)
    {  
        $searchkey = $request->query->get('name');
	$books = $this->bookRepository->searchBook($searchkey);
        $data = $this->toJson($books);
        return new Response($data, 200, ['Content-Type' => 'application/json']);
        //return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="delete_book", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {   
        $book = $this->bookRepository->findOneBy(['id' => $id]);
        $this->bookRepository->removeBook($book);

        return new JsonResponse(['status' => 'Book deleted'], Response::HTTP_NO_CONTENT);
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

