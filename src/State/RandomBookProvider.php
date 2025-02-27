<?php
// src/State/RandomBookProvider.php

namespace App\State;

use App\Repository\BookRepository;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;

class RandomBookProvider implements ProviderInterface
{
     private BookRepository $bookRepository;

     public function __construct(BookRepository $bookRepository)
     {
          $this->bookRepository = $bookRepository;
     }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
         return $this->bookRepository->findRandomBooks();
    }
}
