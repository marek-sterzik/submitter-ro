<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Exception\RequestCorrectionException;
use App\Utility\RequestUtility;
use App\Utility\Cell;

abstract class AbstractTableController extends AbstractController
{
    const DEFAULT_ITEMS_PER_PAGE = 15;

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
            $selfLink = $this->createSelfLink();
            $table = $this->createTable($header, $data, $pageCount, $requestData['page'], $selfLink);
            return $this->render($this->getTemplate(), [
                "table" => $table,
                "self" => $selfLink,
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

    private function createTable(
        array $header,
        array $data,
        int $pageCount,
        int $currentPage,
        callable $selfLink
    ): array {
        $i = 0;
        $mapping = [];
        $finalHeader = [];
        $actionIndex = null;
        foreach ($header as $key => $heading) {
            if ($key === "_actions") {
                $actionIndex = $i;
            }
            $mapping[$key] = $i++;
            $finalHeader[$mapping[$key]] = Cell::cell($heading);
        }
        if ($actionIndex === null) {
            $actionIndex = $i++;
        }
        $body = [];
        foreach ($data as $row) {
            $finalRow = [];
            foreach ($mapping as $key => $index) {
                $finalRow[$index] = Cell::cell($row[$key] ?? null);
            }
            if (isset($row['_actions'])) {
                $finalRow[$actionIndex] = Cell::cell($row['_actions'])->attribute("class", "text-end");
            }
            ksort($finalRow);
            $body[] = $finalRow;
        }
        return [
            "header" => $finalHeader,
            "body" => $body,
            "paginator" => $this->createPaginator($pageCount, $currentPage, $selfLink),
        ];
    }

    private function createPaginator(int $pageCount, int $currentPage, callable $selfLink): array
    {
        $beginEndCount = 1;
        $middleCount = 2;


        $paginator = [];

        $paginator[] = [
            "type" => "prev",
            "label" => "prev",
            "link" => ($currentPage > 0) ? $selfLink(["p" => $this->pageToQuery($currentPage - 1)]) : null,
            "disabled" => ($currentPage > 0) ? false : true,
            "active" => false,
            "skip" => false,
        ];

        $from = 0;
        $limit = min($beginEndCount, $pageCount);

        for ($i = $from; $i < $limit; $i++) {
            $paginator[] = $this->createPaginatorPage($i, $currentPage, false, $selfLink);
        }

        $from = max($limit, $currentPage - $middleCount);
        $skip = ($from > $limit) ? true : false;
        $limit = min($currentPage + $middleCount + 1, $pageCount);
        
        for ($i = $from; $i < $limit; $i++) {
            $paginator[] = $this->createPaginatorPage($i, $currentPage, $skip && ($i === $from), $selfLink);
        }

        $from = max($limit, $pageCount - $beginEndCount);
        $skip = ($from > $limit) ? true : false;
        $limit = $pageCount;

        for ($i = $from; $i < $limit; $i++) {
            $paginator[] = $this->createPaginatorPage($i, $currentPage, $skip && ($i === $from), $selfLink);
        }

        $nextEnabled = ($currentPage < $pageCount - 1) ? true : false;
        $paginator[] = [
            "type" => "next",
            "label" => "next",
            "link" => $nextEnabled ? $selfLink(["p" => $this->pageToQuery($currentPage + 1)]) : null,
            "disabled" => !$nextEnabled,
            "active" => false,
            "skip" => false,
        ];

        return $paginator;
    }

    private function createPaginatorPage(int $page, int $currentPage, bool $skip, callable $selfLink): array
    {
        return [
            "type" => "normal",
            "label" => $page + 1,
            "link" => $selfLink(["p" => $this->pageToQuery($page)]),
            "active" => ($page === $currentPage) ? true : false,
            "disabled" => false,
            "skip" => $skip,
        ];
    }

    private function createSelfLink(): callable
    {
        return function (array $modifyQuery = []) {
            return RequestUtility::modifyUri($this->getRequest(), $modifyQuery);
        };
    }
}
