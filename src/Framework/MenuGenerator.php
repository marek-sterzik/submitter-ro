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
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MenuGenerator
{
    private array $menuTemplate;
    public function __construct(
        private RequestStack $requestStack,
        private AuthorizationCheckerInterface $authorizationChecker,
        string $menuFile
    ) {
        $this->menuTemplate = Yaml::parseFile($menuFile)['menu'];
    }

    public function generateMenu(): array
    {
        $currentRoute = $this->requestStack->getCurrentRequest()->attributes->get('_route');
        $menu = [];
        foreach ($this->menuTemplate as $menuItem) {
            if ($this->granted($menuItem['roles'] ?? null)) {
                $finalItem = $this->createMenuItem($menuItem, $currentRoute);
                if (!$finalItem['hidden'] || $finalItem['actual']) {
                    $menu[] = $this->createMenuItem($menuItem, $currentRoute);
                }
            }
        }
        return $menu;
    }

    private function createMenuItem(array $menuItem, string $currentRoute): array
    {
        $menuItem['hidden'] = $menuItem['hidden'] ?? false;
        $menuItem['target_blank'] = $menuItem['target_blank'] ?? false;
        $menuItem['actual'] = ($menuItem['route'] === $currentRoute) ? true : false;

        return $menuItem;
    }

    private function granted(?array $roles): bool
    {
        $roles = $roles ?? ['ROLE_USER'];
        foreach ($roles as $role) {
            if ($this->authorizationChecker->isGranted($role)) {
                return true;
            }
        }
        return false;
    }
}
