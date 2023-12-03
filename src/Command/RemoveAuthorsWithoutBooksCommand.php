<?php

namespace App\Command;

use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'RemoveAuthorsWithoutBooks',
    description: 'Remove authors without books',
)]
class RemoveAuthorsWithoutBooksCommand extends Command
{
    private $entityManager;
    private $authorRepository;

    public function __construct(EntityManagerInterface $entityManager, AuthorRepository $authorRepository)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->authorRepository = $authorRepository;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:remove-authors-without-books')
            ->setDescription('Remove authors without books');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $authorsWithoutBooks = $this->authorRepository->findAuthorsWithoutBooks();

        foreach ($authorsWithoutBooks as $author) {
            $this->entityManager->remove($author);
        }

        $this->entityManager->flush();

        $output->writeln('Authors without books have been removed.');

        return Command::SUCCESS;
    }
}
