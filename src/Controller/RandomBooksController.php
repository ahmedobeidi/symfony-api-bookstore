<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;

class RandomBooksController extends AbstractController
{
    public function __invoke(EntityManagerInterface $entityManager): JsonResponse
    {
        $connection = $entityManager->getConnection();
        $sql = 'SELECT * FROM book ORDER BY RAND() LIMIT 4';
        $stmt = $connection->prepare($sql);
        $result = $stmt->executeQuery();
        $books = $result->fetchAllAssociative();
        return $this->json($books);
    }
}
