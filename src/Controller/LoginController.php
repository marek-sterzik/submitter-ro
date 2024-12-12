<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route("/login", name: "login")]
    public function login(Request $request): Response
    {
        return $this->getRedirectResponse($request);
    }

    #[Route("/logout", name: "logout")]
    public function logout(Request $request): Response
    {
        return $this->getRedirectResponse($request);
    }

    private function getRedirectResponse(Request $request): Response
    {
        $url = $request->query->get("back");
        if (!is_string($url)) {
            return $this->redirectToRoute('main');
        }
        return $this->redirect($url);
    }
}
