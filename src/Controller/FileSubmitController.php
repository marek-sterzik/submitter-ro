<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\FileSubmitType;

class FileSubmitController extends AbstractController
{
    #[Route("/submit-file")]
    public function index(Request $request): Response
    {
        return $this->form(FileSubmitType::class, [])
        ->action("Uložit", function (array $data) {
            var_dump($data["file"]); die;
            return $this->redirectBack(true);
        })
        ->action("Zrušit", function (User $user) {
            return $this->redirectBack(true);
        }, type: 'btn-secondary')
        ->handle()
    ;
    }
}