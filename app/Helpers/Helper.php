<?php

namespace App\Helpers;

use Carbon\Carbon;

class Helper
{
    /**
     * Get current page and page size
     *
     * @param array $request
     * @return int[]
     */
    public static function getPageSize(array $request = []): array
    {
        $pageSizeFromRequest = array_key_exists('page_size', $request)
            ? (int) ($request['page_size'] ?? 0)
            : 100;
        $pageSize = max(min($pageSizeFromRequest, 200), 10);
        $currentPage = isset($request['page']) ? (int) $request['page'] : 1;

        return [
            'current_page' => $currentPage,
            'page_size'    => $pageSize,
        ];
    }

    /**
     * Check valid date time
     *
     * @param string|null $date
     * @return string|null
     */
    public static function validDate(?string $date): ?string
    {
        try {
            if ($date) {
                return Carbon::parse($date);
            }

            return null;
        } catch (\Exception $exception) {
            return Carbon::parse('2000-01-01')
                ->format('Y-m-d H:i:s');
        }
    }
}
