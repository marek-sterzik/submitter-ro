<?php

namespace App\Framework;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\RequestStack;
use SPSOstrov\SSOBundle\SSOUserDataProviderInterface;
use SPSOstrov\SSOBundle\SSORoleDeciderInterface;
use SPSOstrov\SSOBundle\SSOUser;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use App\Entity\User;

class MenuGenerator
{
    private array $menuTemplate;
    public function __construct(private RequestStack $requestStack, string $menuFile)
    {
        $this->menuTemplate = Yaml::parseFile($menuFile)['menu'];
    }

    public function generateMenu(): array
    {
        $currentRoute = $this->requestStack->getCurrentRequest()->attributes->get('_route');
        $menu = [];
        foreach ($this->menuTemplate as $menuItem) {
            $menu[] = $this->createMenuItem($menuItem, $currentRoute);
        }
        return $menu;
    }

    private function createMenuItem(array $menuItem, string $currentRoute): array
    {
        if (!isset($menuItem['target_blank'])) {
            $menuItem['target_blank'] = false;
        }
        $menuItem['actual'] = ($menuItem['route'] === $currentRoute) ? true : false;

        return $menuItem;
    }
}
