<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\PublisherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/', name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository): JsonResponse
    {
        $books = $bookRepository->findAll();

        $data = [];

        foreach ($books as $book) {
            $data[] = [
                'id' => $book->getId(),
                'name' => $book->getName(),
                'year' => $book->getYear(),
                'publisher' => $book->getPublisher()->getName(),
                'authors' => $book->getAuthor()->map(function (Author $author) {
                    return ['surname' => $author->getSurname()];
                })->toArray(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/new', name: 'app_book_new', methods: ['POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        PublisherRepository $publisher,
        AuthorRepository $author
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || !isset($data['year']) || !isset($data['publisher']) || !isset($data['author'])) {
            return $this->json(["success" => false, "error" => "Bad request"], 400);
        }

        $publisherId = $data['publisher'];
        $authorId = $data['author'];

        $publisher = $publisher->find($publisherId);
        if (!$publisher) {
            return $this->json(["success" => false, "error" => "Publisher not found"], 404);
        }

        $author = $author->find($authorId);
        if (!$author) {
            return $this->json(["success" => false, "error" => "Author not found"], 404);
        }

        $book = new Book();
        $book->setName($data['name']);
        $book->setYear($data['year']);
        $book->setPublisher($publisher);
        $book->addAuthor($author);

        $entityManager->persist($book);
        $entityManager->flush();

        return $this->json(["success" => true]);
    }

    #[Route('/{id}', name: 'app_book_show', methods: ['GET'])]
    public function show(Book $book): JsonResponse
    {
        $data[] = [
            'id' => $book->getId(),
            'name' => $book->getName(),
            'year' => $book->getYear(),
            'publisher' => $book->getPublisher()->getName(),
            'authors' => $book->getAuthor()->map(function (Author $author) {
                return ['surname' => $author->getSurname()];
            })->toArray(),
        ];

        return $this->json($data);
    }

    #[Route('/{id}/edit', name: 'app_book_edit', methods: ['PUT'])]
    public function edit(
        Request $request,
        Book $book,
        EntityManagerInterface $entityManager,
        AuthorRepository $author,
        PublisherRepository $publisher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || !isset($data['year']) || !isset($data['publisher']) || !isset($data['author'])) {
            return $this->json(["success" => false, "error" => "Bad request"], 400);
        }

        $publisherId = $data['publisher'];
        $authorId = $data['author'];

        $publisher = $publisher->find($publisherId);
        if (!$publisher) {
            return $this->json(["success" => false, "error" => "Publisher not found"], 404);
        }

        $author = $author->find($authorId);
        if (!$author) {
            return $this->json(["success" => false, "error" => "Author not found"], 404);
        }

        $book->setName($data['name']);
        $book->setYear($data['year']);
        $book->setPublisher($publisher);
        $book->addAuthor($author);

        $entityManager->flush();

        return $this->json(["success" => "true"]);
    }

    #[Route('/{id}', name: 'app_book_delete', methods: ['DELETE'])]
    public function delete(Request $request, Book $book, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($book);
        $entityManager->flush();

        return $this->json(["success" => "true"]);
    }
}
