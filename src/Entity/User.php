<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class User {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id; // ID de l'utilisateur

    #[ORM\Column(type: "string", length: 255)]
    private string $name; // Nom

    #[ORM\ManyToMany(targetEntity: Book::class)]
    private Collection $borrowedBooks;

    public function getBorrowedBooks(): Collection {
        return $this->borrowedBooks;
    }

    public function __construct(string $name) {
        $this->name = $name;
        $this->borrowedBooks = new ArrayCollection(); // ✅ Initialisation correcte
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    // ✅ SOLID (Encapsulation) : Gère l'emprunt de livres au sein de l'entité utilisateur
    public function borrowBook(Book $book): string {
        // ✅ KISS : Une simple condition pour limiter les emprunts
        if (count($this->borrowedBooks) >= 3) {
            return "Trop de livres empruntés.";
        }
        $this->borrowedBooks[] = $book;
        return "Emprunt réussi.";
    }

    public function returnBook(Book $book): string {
        foreach ($this->borrowedBooks as $key => $borrowedBook) {
            if ($borrowedBook->getId() === $book->getId()) {
                unset($this->borrowedBooks[$key]);
                return "Livre rendu.";
            }
        }
        return "Livre non trouvé.";
    }
}