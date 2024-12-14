<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\QueryBuilder;
use App\Entity\User;
use App\Utility\Cell;

class UsersController extends AbstractDbTableController
{
    #[Route("/users", name: "users")]
    public function index(): Response
    {
        return $this->renderTable();
    }

    protected function getBaseQueryBuilder(array $filterData): QueryBuilder
    {
        return $this->getEntityManager()->getRepository(User::class)->createQueryBuilder('u');
    }

    protected function getHeader(array $filterData): array
    {
        return [
            "me" => "",
            "username" => "uživatelské jméno",
            "name" => "jméno",
            "roles" => "role",
            "class" => "třída",
        ];
    }

    protected function recordToArray(mixed $user): array
    {
        assert($user instanceof User);
        if ($user->isStudent()) {
            $class = Cell::html($this->renderView('snippets/class.html.twig', ["user" => $user]));
        } else {
            $class = null;
        }
        $isMe = ($user === $this->getUser()?->getUserData()) ? true : false;
        $meBadge = $isMe ? (' ' . $this->renderView('snippets/me.html.twig')) : '';
        $roles = $this->renderView('snippets/fundamental-roles.html.twig', ['user' => $user, "full" => true]);
        return [
            "me" => Cell::html($meBadge),
            "username" => $user->getUsername(),
            "name" => $user->getName(),
            "roles" => Cell::html($roles),
            "class" => $class,
        ];
    }
}
