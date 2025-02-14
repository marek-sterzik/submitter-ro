<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;
use App\Entity\Assignment;
use App\Utility\Cell;
use App\Utility\Action;
use App\Utility\SearchTool;
use App\Form\Filter\AssignmentsType;

class AssignmentsController extends AbstractDbTableController
{
    #[IsGranted('ROLE_TEACHER')]
    #[Route("/assignments", name: "assignments")]
    public function index(): Response
    {
        return $this->renderTable();
    }

    protected function getBaseQueryBuilder(array $filterData): QueryBuilder
    {
        $adminView = $this->isGranted("ROLE_ADMIN");
        $me = $this->getUser()->getUserData();
        $qb = $this->getEntityManager()->getRepository(Assignment::class)->createQueryBuilder('a');
        if ($filterData['a']) {
            if (!$adminView) {
                $qb->andWhere($qb->expr()->orX(
                    $qb->expr()->eq("a.owner", ":owner"),
                    $qb->expr()->andX(
                        $qb->expr()->eq("a.published", true),
                        $qb->expr()->eq("a.public", true),
                    )
                ));
                $qb->setParameter(":owner", $me);
            }
        } else {
            $qb->andWhere("a.owner = :owner");
            $qb->setParameter(":owner", $me);

        }
        $searchTool = new SearchTool();
        $searchTool->handle(null, function(QueryBuilder $qb, string $string, ?string $type, string $var) {
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like("a.caption", ":${var}"),
                $qb->expr()->like("a.description", ":${var}"),
            ));
            $qb->setParameter(":${var}", "%$string%");
        });
        $searchTool->search($qb, $filterData['q'] ?? '');
        return $qb;
    }

    protected function getHeader(array $filterData): array
    {
        return [
            "caption" => "název",
            "owner" => "vlastník",
        ];
    }

    protected function recordToArray(mixed $assignment): array
    {
        assert($assignment instanceof Assignment);
        return [
            "caption" => $assignment->getCaption(),
            "owner" => $assignment->getOwner()->getName(),
            "_actions" => $this->getAssignmentActions($assignment),
        ];
    }

    private function getAssignmentActions(Assignment $assignment): array
    {
        return [];
        /*
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
        */
    }

    protected function getForm(array $formData): ?FormInterface
    {
        return $this->createForm(AssignmentsType::class, $formData);
    }

    protected function getDefaultFilterData(): array
    {
        return [
            "a" => false,
        ];
    }
}