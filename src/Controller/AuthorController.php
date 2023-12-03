<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/author')]
class AuthorController extends AbstractController
{
    #[Route('/', name: 'app_author_index', methods: ['GET'])]
    public function index(AuthorRepository $authorRepository): JsonResponse
    {
        $authors = $authorRepository->findAll();

        $data = [];

        foreach ($authors as $author) {
            $data[] = [
                'id' => $author->getId(),
                'name' => $author->getName(),
                'surname' => $author->getSurname(),
                'books' => $author->getBooks()->map(function (Book $book) {
                    return ['name' => $book->getName()];
                })->toArray(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/new', name: 'app_author_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $author = new Author();
        $author->setName($data['name']);
        $author->setSurname($data['surname']);

        $entityManager->persist($author);
        $entityManager->flush();

        return new JsonResponse(["success" => "true"]);
    }

    #[Route('/{id}', name: 'app_author_show', methods: ['GET'])]
    public function show(Author $author): JsonResponse
    {
        $data = [
            'id' => $author->getId(),
            'name' => $author->getName(),
            'surname' => $author->getSurname(),
            'books' => $author->getBooks(),
        ];

        return new JsonResponse($data);
    }

    #[Route('/{id}/edit', name: 'app_author_edit', methods: ['PUT'])]
    public function edit(Request $request, Author $author, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $author->setName($data['name']);
        $author->setSurname($data['surname']);

        $entityManager->flush();

        return new JsonResponse(["success" => "true"]);
    }

    #[Route('/{id}', name: 'app_author_delete', methods: ['DELETE'])]
    public function delete(Request $request, Author $author, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($author);
        $entityManager->flush();

        return new JsonResponse(["success" => "true"]);
    }
}
