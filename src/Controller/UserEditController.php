<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;
use App\Form\UserRolesType;

class UserEditController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route("/user/{user}", name: "user")]
    public function index(User $user): Response
    {
        $superadmin = $this->isGranted('ROLE_SUPERADMIN');
        $options = ["superadmin" => $superadmin, "default_role" => $user->getOriginalRole()];
        return $this->form(UserRolesType::class, $user, $options)
            ->action("Uložit", function (User $user) {
                if ($user->getRealRole() !== 'ROLE_STUDENT') {
                    $user->setEffectiveStudentClass(null);
                }
                $this->getEntityManager()->flush();
                return $this->redirectBack(true);
            })
            ->action("Zrušit", function (User $user) {
                return $this->redirectBack(true);
            }, type: 'btn-secondary')
            ->handle()
        ;
    }

    protected function getDefaultBackUrl(): string
    {
        return $this->generateUrl("users");
    }
}
