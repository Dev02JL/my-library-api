<?php
namespace App\Tests\Entity;

use App\Entity\Book;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase {
    public function testCreateBook() {
        $book = new Book("Le Petit Prince", "Antoine de Saint-Exupéry");

        $this->assertEquals("Le Petit Prince", $book->getTitle());
        $this->assertEquals("Antoine de Saint-Exupéry", $book->getAuthor());
        $this->assertFalse($book->isBorrowed());
    }

    public function testBorrowBook() {
        $book = new Book("1984", "George Orwell");

        $this->assertEquals("Livre emprunté.", $book->borrow());
        $this->assertTrue($book->isBorrowed());

        // Tenter d'emprunter un livre déjà emprunté
        $this->assertEquals("Déjà emprunté.", $book->borrow());
    }

    public function testReturnBook() {
        $book = new Book("Dune", "Frank Herbert");

        $book->borrow();
        $this->assertEquals("Livre retourné.", $book->returnBook());
        $this->assertFalse($book->isBorrowed());

        // Tenter de rendre un livre non emprunté
        $this->assertEquals("Non emprunté.", $book->returnBook());
    }
}