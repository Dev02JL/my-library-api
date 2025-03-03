<?php
namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Book;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class UserTest extends TestCase {
    // Assigner un ID à un livre
    private function setBookId(Book $book, int $id): void {
        $reflection = new ReflectionClass($book);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($book, $id);
    }

    public function testBorrowBook() {
        $user = new User("Alice");
        $book1 = new Book("Livre 1", "Auteur 1");
        $book2 = new Book("Livre 2", "Auteur 2");
        $book3 = new Book("Livre 3", "Auteur 3");
        $book4 = new Book("Livre 4", "Auteur 4");

        $this->assertEquals("Emprunt réussi.", $user->borrowBook($book1));
        $this->assertEquals("Emprunt réussi.", $user->borrowBook($book2));
        $this->assertEquals("Emprunt réussi.", $user->borrowBook($book3));

        // Le quatrième livre dépasse la limite
        $this->assertEquals("Trop de livres empruntés.", $user->borrowBook($book4));
    }

    public function testReturnBook() {
        $user = new User("Bob");
        $book = new Book("Le Seigneur des Anneaux", "J.R.R. Tolkien");
    
        $this->setBookId($book, 1);
    
        $user->borrowBook($book);
        $this->assertEquals("Livre rendu.", $user->returnBook($book));
    
        // Tenter de rendre un livre qui n'a pas été emprunté
        $this->assertEquals("Livre non trouvé.", $user->returnBook($book));
    }
}