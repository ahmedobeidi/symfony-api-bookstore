<?php
// src/State/RandomBookProvider.php

namespace App\State;

use App\Repository\BookRepository;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;

class RandomBookProvider implements ProviderInterface
{

     public function __construct(private BookRepository $bookRepository)
     {

     }

     public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
     {
         return $this->bookRepository->findRandomBooks();
     }

}
