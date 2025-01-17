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
        return $this->form(FileSubmitType::class, [], ["attr" => ["class" => "with-progress"]])
        ->action("Uložit", function (array $data) {
            return $this->redirectBack(true);
        })
        ->action("Zrušit", function (array $data) {
            return $this->redirectBack(true);
        }, type: 'btn-secondary', validated: false)
        ->handle()
        ;
    }
}
