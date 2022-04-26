<?php

namespace App\Service\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManagerService
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    public function createAdminUser(string $username, string $password): User
    {
        $user = new User();
        $user->setEmail($username);
        $user->setPassword($this->encodePassword($user, $password));
        $user->setRoles(['ROLE_ADMIN']);
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    private function encodePassword(User $user, string $password): string
    {
        return $this->passwordHasher->hashPassword($user, $password);
    }
}
