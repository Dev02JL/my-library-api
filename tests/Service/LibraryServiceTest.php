<?php
namespace App\Tests\Service;

use App\Entity\Book;
use App\Entity\User;
use App\Service\LibraryService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository; // ⚠ Corrigé : Utilisation du bon type
use PHPUnit\Framework\TestCase;

class LibraryServiceTest extends TestCase {
    private $libraryService;
    private $entityManager;
    private $bookRepository;
    private $userRepository;

    protected function setUp(): void {
        // Mock EntityManager
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        // Mock Repositories (⚠ Corrigé : Utilisation de EntityRepository au lieu de ObjectRepository)
        $this->bookRepository = $this->createMock(EntityRepository::class);
        $this->userRepository = $this->createMock(EntityRepository::class);

        // Simule que l'EntityManager retourne les repositories
        $this->entityManager
            ->method('getRepository')
            ->willReturnMap([
                [Book::class, $this->bookRepository],
                [User::class, $this->userRepository]
            ]);

        // Création du service avec l'EntityManager mocké
        $this->libraryService = new LibraryService($this->entityManager);
    }

    public function testAddBook() {
        $this->entityManager->expects($this->once())->method('persist');
        $this->entityManager->expects($this->once())->method('flush');

        $this->libraryService->addBook("Harry Potter", "J.K. Rowling");
        $this->assertTrue(true); // Vérifie qu'il n'y a pas d'erreur
    }

    public function testBorrowBookSuccess() {
        $book = new Book("Les Misérables", "Victor Hugo");
        $user = new User("Jean Valjean");

        // Simule un livre et un utilisateur existant en base
        $this->bookRepository->method('findOneBy')->willReturn($book);
        $this->userRepository->method('find')->willReturn($user);

        $message = $this->libraryService->borrowBook("Les Misérables", 1);
        $this->assertEquals("Emprunt réussi.", $message);
    }

    public function testBorrowBookAlreadyTaken() {
        $book = new Book("Les Misérables", "Victor Hugo");
        $user = new User("Jean Valjean");

        // Simule un livre déjà emprunté
        $book->borrow();
        $this->bookRepository->method('findOneBy')->willReturn($book);
        $this->userRepository->method('find')->willReturn($user);

        $message = $this->libraryService->borrowBook("Les Misérables", 1);
        $this->assertEquals("Livre déjà emprunté.", $message);
    }

    public function testBorrowBookNotFound() {
        $this->bookRepository->method('findOneBy')->willReturn(null);
        $this->userRepository->method('find')->willReturn(null);

        $message = $this->libraryService->borrowBook("Inexistant", 999);
        $this->assertEquals("Livre ou utilisateur introuvable.", $message);
    }
}