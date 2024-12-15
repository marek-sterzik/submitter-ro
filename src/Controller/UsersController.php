<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\QueryBuilder;
use App\Entity\User;
use App\Utility\Cell;
use App\Utility\Action;

class UsersController extends AbstractDbTableController
{
    #[IsGranted('ROLE_ADMIN')]
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
            "username" => "uživatelské jméno",
            "name" => "jméno",
            "roles" => "role",
            "class" => "třída",
        ];
    }

    protected function recordToArray(mixed $user): array
    {
        assert($user instanceof User);
        $class = Cell::html($this->renderView('snippets/class.html.twig', ["user" => $user]));
        $isMe = ($user === $this->getUser()?->getUserData()) ? true : false;
        $meBadge = $isMe ? (' ' . $this->renderView('snippets/me.html.twig')) : '';
        $roles = $this->renderView('snippets/fundamental-roles.html.twig', ['user' => $user, "full" => true]);
        return [
            "username" => Cell::html(htmlspecialchars($user->getUsername()) . $meBadge),
            "name" => $user->getName(),
            "roles" => Cell::html($roles),
            "class" => $class,
            "_actions" => $this->getUserActions($user),
        ];
    }

    private function getUserActions(User $user): ?array
    {
        return [
            Action::get(
                $this->generateUrl("user", ["user" => $user->getId(), "_back" => true]),
                "nastavit roli",
                "btn-primary"
            )
        ];
    }
}
