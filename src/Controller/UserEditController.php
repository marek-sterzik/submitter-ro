<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;
use App\Form\UserRolesType;
use App\Utility\RoleComparator;

class UserEditController extends AbstractController
{

    #[IsGranted('ROLE_ADMIN')]
    #[Route("/user/{user}", name: "user")]
    public function index(User $user): Response
    {
        $superadmin = $this->isGranted('ROLE_SUPERADMIN');
        $restorableRole = $this->getRestorableRole($user, $superadmin);
        $options = ["superadmin" => $superadmin, "default_role" => $user->getOriginalRole()];
        return $this->form(UserRolesType::class, $user, $options)
            ->action("Uložit", function (User $user) use ($superadmin, $restorableRole) {
                if ($user->getRealRole() !== 'ROLE_STUDENT') {
                    $user->setEffectiveStudentClass(null);
                }
                $user->setRestorableRole($restorableRole);
                $this->getEntityManager()->flush();
                return $this->redirectBack(true);
            })
            ->action("Zrušit", function (User $user) {
                return $this->redirectBack(true);
            }, type: 'btn-secondary')
            ->handle()
        ;
    }

    private function getRestorableRole(User $user, bool $superadmin): ?string
    {
        $userMaxRole = $user->getRealRole();
        if ($user->getRestorableRole() !== null) {
            $userMaxRole = RoleComparator::max($userMaxRole, $user->getRestorableRole());
        }
        if (($user->getRealRole() === 'ROLE_SUPERADMIN' && !$superadmin) || $user === $this->getUser()->getUserData()) {
            return $userMaxRole;
        }

        return null;
    }

    protected function getDefaultBackUrl(): string
    {
        return $this->generateUrl("users");
    }
}
