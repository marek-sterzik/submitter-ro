<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as AbstractControllerBase;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Framework\MenuGenerator;

class AbstractController extends AbstractControllerBase
{
    private MenuGenerator $menuGenerator;

    public function setMenuGenerator(MenuGenerator $menuGenerator): self
    {
        $this->menuGenerator = $menuGenerator;
        return $this;
    }

    protected function getDefaultParameters(): array
    {
        return [
            "menu" => $this->menuGenerator->generateMenu(),
            "user" => $this->getUser(),
        ];
    }

    protected function renderView(string $view, array $parameters = []): string
    {
        $parameters = array_merge($this->getDefaultParameters(), $parameters);
        return parent::renderView($view, $parameters);
    }

    protected function render(
        string $view,
        array $parameters = [],
        ?Response $response = null
    ): Response {
        $parameters = array_merge($this->getDefaultParameters(), $parameters);
        return parent::render($view, $parameters, $response);
    }
}
