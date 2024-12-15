<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;
use App\Form\UserRolesType;
use App\Utility\RoleComparator;

class UserRestoreRoleController extends AbstractController
{

    #[IsGranted('ROLE_USER')]
    #[Route("/restore-role", name: "restore_role")]
    public function index(): Response
    {
        $user = $this->getUser()->getUserData();
        return $this->indexUser($user);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route("/restore-role/{user}", name: "restore_role_user")]
    public function indexUser(User $user): Response
    {
        if ($user->isRoleRestorable()) {
            $user->restoreRole();
            $this->getEntityManager()->flush();
        }
        return $this->redirectBack(true);
    }

    protected function getDefaultBackUrl(): string
    {
        return $this->generateUrl("user_detail");
    }
}
