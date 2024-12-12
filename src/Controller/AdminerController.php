<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminerController extends AbstractController
{
    public function __construct(
        private string $databaseUrl,
        private string $adminerBootFile
    ) {
    }

    #[IsGranted('ROLE_SUPERADMIN')]
    #[Route("/adminer", name: "app.adminer")]
    public function pageAdminer(): Response
    {
        return new StreamedResponse(
            function () {
                $httpAuth = null;
                $dbConf = $this->databaseUrl;
                include $this->adminerBootFile;
            }
        );
    }
}
