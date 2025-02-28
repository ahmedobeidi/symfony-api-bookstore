<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserProcessor implements ProcessorInterface
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        private EntityManagerInterface $entityManager
    )
    {
        
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User
    {
        if ($data instanceof User) 
        {
            $hashedPassword = $this->userPasswordHasher->hashPassword($data, $data->getPassword());
            $data->setPassword($hashedPassword);

            $data->setRoles(['ROLE_USER']);

            $this->entityManager->persist($data);
            $this->entityManager->flush();

            return $data;
        }
    }
}
