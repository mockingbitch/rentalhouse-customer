<?php

namespace App\Core\Database;

use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Performs settings for debugging executed queries.
 *
 * @package App\Core\Database
 */
class QueryDebugger
{
    /**
     * Set up the debugger
     *
     * @return void
     */
    public static function setup(): void
    {
        if (config('database.debug', false)) {
            DB::listen(function ($sql) {
                foreach ($sql->bindings as $index => $binding) {
                    if ($binding instanceof DateTime) {
                        $sql->bindings[$index] = $binding->format('\'Y-m-d H:i:s\'');
                    } else {
                        if (is_string($binding)) {
                            $sql->bindings[$index] = "'$binding'";
                        }
                    }
                }

                $query = str_replace([ '%', '?' ], [ '%%', '%s' ], $sql->sql);
                $query = vsprintf($query, $sql->bindings);
                $query = "[ExecutionTime: {$sql->time}ms] {$query}";
                Log::channel('sql')->info($query);
            });
        }
    }
}
