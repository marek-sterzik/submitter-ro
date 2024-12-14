<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Exception\RequestCorrectionException;

abstract class AbstractTableController extends AbstractController
{
    const DEFAULT_ITEMS_PER_PAGE = 20;

    abstract protected function getItemCount(array $filterData): int;
    abstract protected function getHeader(array $filterData): array;
    abstract protected function getData(int $page, int $pageSize, array $filterData): array;

    protected function renderTable(): Response
    {
        try {
            $requestData = $this->getRequestData($this->getRequest());
            $itemCount = $this->getItemCount($requestData['filterData']);
            $pageCount = $this->getNumberOfPages($requestData['itemsPerPage'], $itemCount);
            if ($requestData['page'] >= $pageCount) {
                throw new RequestCorrectionException($this->getRequest(), ["p" => $this->pageToQuery($pageCount - 1)]);
            }
            $header = $this->getHeader($requestData['filterData']);
            $data = $this->getData($requestData['page'], $requestData['itemsPerPage'], $requestData['filterData']);
            $table = $this->createTable($header, $data);
            return $this->render($this->getTemplate(), [
                "table" => $table,
            ]);
        } catch (RequestCorrectionException $e) {
            return $e->getRedirectResponse();
        }
    }

    protected function getTemplate(): string
    {
        return "table.html.twig";
    }

    protected function getRequestData(Request $request): array
    {
        $requestData = [
            "page" => $this->getPage($request),
            "itemsPerPage" => $this->getItemsPerPage($request),
            "filterData" => $this->getFilterData($request),
        ];
        return $requestData;
    }

    protected function getFilterData(Request $request): array
    {
        return [];
    }

    protected function getItemsPerPage(Request $request): int
    {
        return self::DEFAULT_ITEMS_PER_PAGE;
    }

    private function getNumberOfPages(int $itemsPerPage, int $itemCount): int
    {
        return max(1, intdiv($itemCount + $itemsPerPage - 1, $itemsPerPage));
    }

    private function getPage(Request $request): int
    {
        $page = $request->query->get("p");
        if ($page !== null) {
            if (!preg_match('/^[0-9]+$/', $page)) {
                throw new RequestCorrectionException($request, ["p" => null]);
            }
            $page = ((int)$page) - 1;
            if ($page < 0) {
                throw new RequestCorrectionException($request, ["p" => null]);
            }
            return $page;
        }
        return 0;
    }

    private function pageToQuery(int $page): ?string
    {
        if ($page === 0) {
            return null;
        }
        return (string)($page + 1);
    }

    private function createTable(array $header, array $data): array
    {
        $i = 0;
        $mapping = [];
        $finalHeader = [];
        foreach ($header as $key => $heading) {
            $mapping[$key] = $i++;
            $finalHeader[$mapping[$key]] = $heading;
        }
        $body = [];
        foreach ($data as $row) {
            $finalRow = [];
            foreach ($mapping as $key => $index) {
                $finalRow[$index] = $row[$key] ?? null;
            }
            ksort($finalRow);
            $body[] = $finalRow;
        }
        return [
            "header" => $finalHeader,
            "body" => $body,
        ];
    }
}
