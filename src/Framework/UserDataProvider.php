<?php

namespace App\Framework;

use DateTimeImmutable;
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

    public function getUserData(SSOUser $user): mixed
    {
        $loginAt = (new DateTimeImmutable())->setTimestamp($user->getLoginTimestamp());
        $userEntity = $this->userRepository->findOneBy(["username" => $user->getLogin()]);
        $changed = false;
        if ($userEntity === null) {
            $firstUser = ($this->userRepository->countUsers() === 0) ? true : false;
            $userEntity = new User($user->getLogin());
            $this->entityManager->persist($userEntity);
            if ($firstUser) {
                $userEntity->setRoles(['ROLE_SUPERADMIN']);
            }
            $changed = true;
            $update = true;
        } else {
            $update = ($userEntity->getLastLoginAt() === null || $userEntity->getLastLoginAt() <= $loginAt);
        }

        if ($update) {
            if ($userEntity->getLastLoginAt()?->getTimestamp() !== $loginAt->getTimestamp()) {
                $userEntity->setLastLoginAt($loginAt);
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

            $studentClass = $user->isStudent() ? strtoupper($user->getClass() ?? '?') : null;
            if ($userEntity->getStudentClass() !== $studentClass) {
                $userEntity->setStudentClass($studentClass);
                $changed = true;
            }
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

        return $userEntity->getFundamentalRoles();
    }
}
