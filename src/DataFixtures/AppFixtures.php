<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Publisher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $author1 = new Author();
        $author1->setName('Автор 1');
        $author1->setSurname('Фамилия 1');
        $manager->persist($author1);

        $author2 = new Author();
        $author2->setName('Автор 2');
        $author2->setSurname('Фамилия 2');
        $manager->persist($author2);

        $publisher1 = new Publisher();
        $publisher1->setName('Издательство 1');
        $publisher1->setAddress('Адрес 1');
        $manager->persist($publisher1);

        $publisher2 = new Publisher();
        $publisher2->setName('Издательство 2');
        $publisher2->setAddress('Адрес 2');
        $manager->persist($publisher2);

        $book1 = new Book();
        $book1->setName('Книга 1');
        $book1->setYear('2000');
        $book1->addAuthor($author1);
        $book1->setPublisher($publisher1);
        $manager->persist($book1);

        $book2 = new Book();
        $book2->setName('Книга 2');
        $book2->setYear('2002');
        $book2->addAuthor($author2);
        $book2->setPublisher($publisher2);
        $manager->persist($book2);

        $manager->flush();
    }
}
