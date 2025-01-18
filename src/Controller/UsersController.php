<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;
use App\Entity\User;
use App\Utility\Cell;
use App\Utility\Action;
use App\Utility\SearchTool;
use App\Form\Filter\UsersType;

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
        $qb = $this->getEntityManager()->getRepository(User::class)->createQueryBuilder('u');
        if ($filterData['t'] === UsersType::TYPE_STUDENTS) {
            $this->roleQuery($qb, $filterData['a'], ['ROLE_STUDENT']);
        } elseif ($filterData['t'] === UsersType::TYPE_TEACHERS) {
            $this->roleQuery($qb, $filterData['a'], ['ROLE_TEACHER', 'ROLE_ADMIN', 'ROLE_SUPERADMIN', 'ROLE_OTHER']);
        }
        $searchTool = new SearchTool();
        $searchTool->handle(null, function(QueryBuilder $qb, string $string, ?string $type, string $var) {
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like("u.username", ":${var}"),
                $qb->expr()->like("u.name", ":${var}"),
                $qb->expr()->like("u.effectiveStudentClass", ":${var}_start"),
                $qb->expr()->andX(
                    $qb->expr()->isNull("u.effectiveStudentClass"),
                    $qb->expr()->like("u.originalStudentClass", ":${var}_start"),
                )
            ));
            $qb->setParameter(":${var}", "%$string%");
            $qb->setParameter(":${var}_start", "$string%");
        });
        $searchTool->search($qb, $filterData['q'] ?? '');
        return $qb;
    }

    private function roleQuery(QueryBuilder $qb, bool $includeOriginal, array $roles): void
    {
        if ($includeOriginal) {
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->in("u.effectiveRole", $roles),
                $qb->expr()->in("u.originalRole", $roles),
            ));
        } else {
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->in("u.effectiveRole", $roles),
                $qb->expr()->andX(
                    $qb->expr()->in("u.originalRole", $roles),
                    $qb->expr()->isNull("u.effectiveRole"),
                )
            ));
        }
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

    private function getUserActions(User $user): array
    {
        $actions = [
            Action::get(
                $this->generateUrl("user", ["user" => $user->getId(), "_back" => true]),
                "nastavit roli",
                "btn-primary"
            )
        ];
        if ($user->isRoleRestorable()) {
            array_unshift($actions, Action::get(
                $this->generateUrl("restore_role_user", ["user" => $user->getId(), "_back" => true]),
                "obnovit roli",
                "btn-danger me-2"
            ));
        }
        return $actions;
    }

    protected function getForm(array $formData): ?FormInterface
    {
        return $this->createForm(UsersType::class, $formData);
    }

    protected function getDefaultFilterData(): array
    {
        return [
            "t" => UsersType::TYPE_ALL,
            "a" => false,
        ];
    }
}
