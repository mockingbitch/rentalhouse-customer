<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Paginator
{
    private string $pageName = 'page';
    private int $perPage = 20;
    private $currentPage;
    private bool $perPageFlag = false;
    private string $perPageName = 'per_page';

    /**
     * Set Current Page
     *
     * @param mixed $currentPage
     */
    public function setCurrentPage(mixed $currentPage): void
    {
        $this->currentPage = $currentPage;
    }

    /**
     * Get Current Page
     *
     * @return int
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getCurrentPage(): int
    {
        $page = request()->get($this->getPageName()) ?? 1;

        return (int) $page;
    }

    /**
     * Get per page
     *
     * @param mixed $perPage
     */
    public function setPerPage(mixed $perPage): void
    {
        $this->perPage = $perPage;
        $this->perPageFlag = true;
    }

    /**
     * Get per page
     *
     * @return int
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getPerPage(): int
    {
        $parPage = (int) request()->get($this->perPageName);
        if(!$this->perPageFlag && $parPage > 0){
            return $parPage;
        }
        return $this->perPage;
    }

    /**
     * Get page name
     *
     * @return string
     */
    public function getPageName(): string
    {
        return $this->pageName;
    }

    /**
     * Set page name
     *
     * @param mixed $pageName
     * @return void
     */
    public function setPageName(mixed $pageName): void
    {
        $this->pageName = $pageName;
    }

    /**
     * Get offset
     *
     * @return float|int
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    final public function getOffset(): float|int
    {
        $offset = $this->getCurrentPage() - 1 > 0 ? $this->getCurrentPage() - 1 : 0 ;

        return $offset * $this->getPerPage();
    }

    /**
     * Get per page name
     *
     * @return string
     */
    public function getPerPageName(): string
    {
        return $this->perPageName;
    }

    /**
     * Set per page name
     *
     * @param $perPageName
     */
    public function setPerPageName($perPageName): void
    {
        $this->perPageName = $perPageName;
    }

    /**
     * Get current page and page size
     *
     * @return int[]
     */
    public static function getPageSize(): array
    {
        $request = request()->toArray();
        $pageSizeFromRequest = array_key_exists('limit', $request)
            ? (int) ($request['limit'] ?? 0)
            : 20;
        $pageSize = max(min($pageSizeFromRequest, 50), 20);
        $currentPage = isset($request['page']) ? (int) $request['page'] : 1;

        return [
            'page'  => $currentPage,
            'limit' => $pageSize,
        ];
    }
}
