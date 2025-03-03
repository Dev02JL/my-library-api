<?php
namespace App\Service;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class LibraryService {
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    // ✅ SOLID (Single Responsibility) : Chaque méthode a une seule responsabilité
    public function addBook(string $title, string $author): void {
        $book = new Book($title, $author);
        $this->em->persist($book);
        $this->em->flush();
    }

    public function borrowBook(string $title, int $userId): string {
        $book = $this->em->getRepository(Book::class)->findOneBy(['title' => $title]);
        $user = $this->em->getRepository(User::class)->find($userId);

        if (!$book || !$user) {
            return "Livre ou utilisateur introuvable.";
        }

        return $this->processBorrow($user, $book);
    }

    // ✅ DRY : processBorrow() permet d’éviter la duplication de code pour l’emprunt.
    private function processBorrow(User $user, Book $book): string {
        if ($book->isBorrowed()) {
            return "Livre déjà emprunté.";
        }

        $user->borrowBook($book);
        $book->borrow();

        $this->em->flush();
        return "Emprunt réussi.";
    }

    public function returnBook(string $title, int $userId): string {
        $book = $this->em->getRepository(Book::class)->findOneBy(['title' => $title]);
        $user = $this->em->getRepository(User::class)->find($userId);
    
        if (!$book || !$user) {
            return "Livre ou utilisateur introuvable.";
        }
    
        return $this->processReturn($user, $book);
    }
    
    private function processReturn(User $user, Book $book): string {
        return $user->returnBook($book);
    }
}