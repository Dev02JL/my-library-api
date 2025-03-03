<?php
namespace App\Controller;

use App\Service\LibraryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// ✅ SOLID (Single Responsibility) : Ce contrôleur ne fait que rediriger vers le service LibraryService.
// ✅ KISS : Simplicité et clarté du code.
// ✅ DRY : Pas de logique métier répétée dans le contrôleur.
#[Route('/library')]
class LibraryController extends AbstractController {
    private LibraryService $libraryService;

    public function __construct(LibraryService $libraryService) {
        $this->libraryService = $libraryService;
    }

    #[Route('/add-book', methods: ['POST'])]
    public function addBook(Request $request): JsonResponse {
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['title']) || !isset($data['author'])) {
            return new JsonResponse(['error' => 'Données incomplètes'], Response::HTTP_BAD_REQUEST);
        }

        $this->libraryService->addBook($data['title'], $data['author']);
        return new JsonResponse(['message' => 'Livre ajouté.']);
    }

    #[Route('/borrow', methods: ['POST'])]
    public function borrowBook(Request $request): JsonResponse {
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['title']) || !isset($data['userId'])) {
            return new JsonResponse(['error' => 'Données incomplètes'], Response::HTTP_BAD_REQUEST);
        }

        $message = $this->libraryService->borrowBook($data['title'], (int)$data['userId']);
        return new JsonResponse(['message' => $message]);
    }

    #[Route('/return', methods: ['POST'])]
    public function returnBook(Request $request): JsonResponse {
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['title']) || !isset($data['userId'])) {
            return new JsonResponse(['error' => 'Données incomplètes'], Response::HTTP_BAD_REQUEST);
        }

        $message = $this->libraryService->returnBook($data['title'], (int)$data['userId']);
        return new JsonResponse(['message' => $message]);
    }
}