<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractTableController
{
    #[Route("/users", name: "users")]
    public function index(): Response
    {
        return $this->renderTable();
    }

    protected function getItemCount(array $filterData): int
    {
        return 25;
    }

    protected function getHeader(array $filterData): array
    {
        return [
            "username" => "Uživatelské jméno",
            "name" => "Jméno",
        ];
    }

    protected function getData(int $page, int $pageSize, array $filterData): array
    {
        $from = $page * $pageSize;
        $to = min($from + $pageSize, $this->getItemCount($filterData));
        $data = [];
        for ($i = $from; $i < $to; $i++) {
            $data[] = ["username" => "john.doe." . ($i+1), "name" => "John Doe " . ($i+1)];
        }
        return $data;
    }
}
