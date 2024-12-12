<?php

namespace App\Framework;

use SPSOstrov\SSOBundle\SSOUserDataProviderInterface;
use SPSOstrov\SSOBundle\SSORoleDeciderInterface;
use SPSOstrov\SSOBundle\SSOUser;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use App\Entity\User;

class UserDataProvider implements SSOUserDataProviderInterface, SSORoleDeciderInterface
{
    public function __construct(private UserRepository $userRepository, private EntityManager $entityManager)
    {
    }

    public function getUserData(SSOUser $user)
    {
        $userEntity = $this->userRepository->findOneBy(["username" => $user->getLogin()]);
        $changed = false;
        if ($userEntity === null) {
            $userEntity = new User($user->getLogin());
            $this->entityManager->persist($userEntity);
            $changed = true;
        }
        if ($userEntity->getName() !== $user->getName()) {
            $userEntity->setName($user->getName());
            $changed = true;
        }

        if ($userEntity->getName() !== $user->getName()) {
            $userEntity->setName($user->getName());
            $changed = true;
        }

        if ($userEntity->isTeacher() !== $user->isTeacher()) {
            $userEntity->setTeacher($user->isTeacher());
            $changed = true;
        }

        if ($changed) {
            $this->entityManager->flush();
        }

        return $userEntity;
    }

    public function decideRoles(SSOUser $user): array
    {
        $userEntity = $user->getUserData();
        assert($userEntity instanceof User);
        $storedRoles = array_unique($userEntity->getRoles() ?? ['ROLE_DEFAULT']);

        $roles = [];
        foreach ($storedRoles as $role) {
            if ($role === 'ROLE_DEFAULT') {
                $roles = array_merge($roles, $this->getDefaultRoles($user));
            } elseif (is_string($role)) {
                $roles[] = $role;
            }
        }

        return $roles;
    }

    public function getDefaultRoles(SSOUser $user): array
    {
        $role = $user->isTeacher() ? 'ROLE_TEACHER' : ($user->isStudent() ? 'ROLE_STUDENT' : 'ROLE_OTHER');
        return [$role];
    }
}
