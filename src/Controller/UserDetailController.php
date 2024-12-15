<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserDetailController extends AbstractController
{
    #[Route("/user-detail", name: "user_detail")]
    public function index(): Response
    {
        $restoreRoleUrl = $this->generateUrl('restore_role', ["_back" => true]);
        return $this->render('user-detail.html.twig', ["restoreRoleUrl" => $restoreRoleUrl]);
    }
}
