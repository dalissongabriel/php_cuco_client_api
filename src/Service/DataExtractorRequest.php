<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\Request;

class DataExtractorRequest
{
    private function getRequestData(Request $request)
    {
        $queryString = $request->query->all();

        $sortInfo = null;
        if (array_key_exists("sort", $queryString)) {
            $sortInfo =  $queryString["sort"];
            unset($queryString['sort']);
        }

        $currentPage = 1;
        if (array_key_exists("page",$queryString)) {
            $currentPage = $queryString['page'];
            unset($queryString["page"]);
        }

        $itemsPerPage = 5;
        if (array_key_exists("itemsPerPage", $queryString)) {
            $itemsPerPage = $queryString["itemsPerPage"];
            unset($queryString["itemsPerPage"]);
        }

        $filterInfo = $queryString;

        return [$sortInfo, $filterInfo, $currentPage, $itemsPerPage];
    }
    public function getSortData(Request $request)
    {
        [$sortInfo,,, ] = $this->getRequestData($request);
        return $sortInfo;
    }

    public function getFilterData(Request $request)
    {
        [, $filterInfo,,] = $this->getRequestData($request);
        return $filterInfo;
    }

    public function getPaginationData(Request $request)
    {
        [,, $currentPage, $itemsPerPage] = $this->getRequestData($request);
        return [$currentPage, $itemsPerPage];
    }
}