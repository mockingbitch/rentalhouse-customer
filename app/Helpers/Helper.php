<?php

namespace App\Helpers;

use Carbon\Carbon;

class Helper
{
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
