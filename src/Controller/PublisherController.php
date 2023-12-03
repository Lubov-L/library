<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Publisher;
use App\Repository\PublisherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/publisher')]
class PublisherController extends AbstractController
{
    #[Route('/', name: 'app_publisher_index', methods: ['GET'])]
    public function index(PublisherRepository $publisherRepository): JsonResponse
    {
        $publishers = $publisherRepository->findAll();

        $data = [];
        foreach ($publishers as $publisher) {
            $data[] = [
                'id' => $publisher->getId(),
                'name' => $publisher->getName(),
                'address' => $publisher->getAddress(),
                'books' => $publisher->getBooks()->map(function (Book $book) {
                    return ['name' => $book->getName()];
                })->toArray(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/new', name: 'app_publisher_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || !isset($data['address'])) {
            return $this->json(["success" => false, "error" => "Bad request"], 400);
        }

        $publisher = new Publisher();
        $publisher->setName($data['name']);
        $publisher->setAddress($data['address']);

        $entityManager->persist($publisher);
        $entityManager->flush();

        return $this->json(["success" => "true"]);
    }

    #[Route('/{id}', name: 'app_publisher_show', methods: ['GET'])]
    public function show(Publisher $publisher): JsonResponse
    {
        $data[] = [
            'id' => $publisher->getId(),
            'name' => $publisher->getName(),
            'address' => $publisher->getAddress(),
            'books' => $publisher->getBooks()->map(function (Book $book) {
                return ['name' => $book->getName()];
            })->toArray(),
        ];

        return $this->json($data);
    }

    #[Route('/{id}/edit', name: 'app_publisher_edit', methods: ['PUT'])]
    public function edit(Request $request, Publisher $publisher, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || !isset($data['address'])) {
            return $this->json(["success" => false, "error" => "Bad request"], 400);
        }

        $publisher->setName($data['name']);
        $publisher->setAddress($data['address']);

        $entityManager->flush();

        return $this->json(["success" => "true"]);
    }

    #[Route('/{id}', name: 'app_publisher_delete', methods: ['DELETE'])]
    public function delete(Request $request, Publisher $publisher, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($publisher);
        $entityManager->flush();

        return $this->json(["success" => "true"]);
    }
}
