<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity]
class Book {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id; // ID

    #[ORM\Column(type: "string", length: 255)]
    private string $title; // Titre

    #[ORM\Column(type: "string", length: 255)]
    private string $author; // Auteur

    #[ORM\Column(type: "boolean")]
    private bool $isBorrowed = false; // Statut d'emprunt

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?DateTime $borrowDate = null; // Date d'emprunt

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?DateTime $returnDate = null; // Date de retour

    // ✅ KISS : Constructeur simple et explicite
    public function __construct(string $title, string $author) {
        $this->title = $title;
        $this->author = $author;
    }

    // ✅ SOLID (Encapsulation) : Utilisation de getters pour ne pas exposer directement les propriétés
    public function getId(): int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getAuthor(): string {
        return $this->author;
    }

    public function isBorrowed(): bool {
        return $this->isBorrowed;
    }

    // ✅ DRY : Évite la répétition de code pour l'emprunt et le retour
    public function borrow(): string {
        if ($this->isBorrowed) {
            return "Déjà emprunté.";
        }
        $this->isBorrowed = true;
        $this->borrowDate = new DateTime();
        return "Livre emprunté.";
    }

    public function returnBook(): string {
        if (!$this->isBorrowed) {
            return "Non emprunté.";
        }
        $this->isBorrowed = false;
        $this->returnDate = new DateTime();
        return "Livre retourné.";
    }
}