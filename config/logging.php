<?php

use App\Core\Log\LogFormatter;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Processor\PsrLogMessageProcessor;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that is utilized to write
    | messages to your logs. The value provided here should match one of
    | the channels present in the list of "channels" configured below.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the log channel that should be used to log warnings
    | regarding deprecated PHP and library features. This allows you to get
    | your application ready for upcoming major versions of dependencies.
    |
    */

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace' => env('LOG_DEPRECATIONS_TRACE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Laravel
    | utilizes the Monolog PHP logging library, which includes a variety
    | of powerful log handlers and formatters that you're free to use.
    |
    | Available drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog", "custom", "stack"
    |
    */

    'channels' => [

        'stack' => [
            'driver' => 'stack',
            'channels' => explode(',', env('LOG_STACK', 'single')),
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => env('LOG_DAILY_DAYS', 14),
            'replace_placeholders' => true,
            'tap' => [App\Core\Log\LogDailyFormatter::class], // Add tap to channel
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => env('LOG_SLACK_USERNAME', 'Laravel Log'),
            'emoji' => env('LOG_SLACK_EMOJI', ':boom:'),
            'level' => env('LOG_LEVEL', 'critical'),
            'replace_placeholders' => true,
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => env('LOG_PAPERTRAIL_HANDLER', SyslogUdpHandler::class),
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
                'connectionString' => 'tls://'.env('PAPERTRAIL_URL').':'.env('PAPERTRAIL_PORT'),
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
            'facility' => env('LOG_SYSLOG_FACILITY', LOG_USER),
            'replace_placeholders' => true,
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],

        //Custom log
        'sql' => [
            'driver' => 'daily',
            'path'   => storage_path('logs/sql.log'),
            'level'  => 'info',
            'tap'    => [
                LogFormatter::class . ':' . implode(',', [
                    "datetime:%datetime%\t%message%" . PHP_EOL,
                    'Y/m/d H:i:s'
                ])
            ],
        ],
        'warning' => [
            'driver' => 'single',
            'path' => storage_path('logs/warn.log'),
            'level' => 'notice',
            'tap'    => [
                LogFormatter::class . ':' . implode(',', [
                    '[%datetime%] [%level_name%] %extra.class% <%extra.function%(%extra.line%)> %message% %context%' . PHP_EOL,
                    'Y-m-d H:i:s:v'
                ])
            ],
        ],
        'info' => [
            'driver' => 'single',
            'path' => storage_path('logs/info.log'),
            'tap'    => [
                LogFormatter::class . ':' . implode(',', [
                    '[%datetime%] [%level_name%] %message% %context%' . PHP_EOL,
                    'Y/m/d H:i:s'
                ])
            ],
        ],
        'fatal' => [
            'driver' => 'daily',
            'path'   => storage_path('logs/fatal.log'),
            'level'  => 'error',
            'tap'    => [
                LogFormatter::class . ':' . implode(',', [
                    '[%datetime%] [%level_name%] %extra.class% <%extra.function%(%extra.line%)> %message% %context%' . PHP_EOL,
                    'Y-m-d H:i:s:v'
                ])
            ],
        ],
        'exception' => [
            'driver' => 'daily',
            'path'   => storage_path('logs/exception.log'),
            'level'  => 'warning',
            'tap'    => [
                LogFormatter::class . ':' . implode(',', [
                    '[%datetime%] [%level_name%] %extra.class% <%extra.function%(%extra.line%)> %message% %context%' . PHP_EOL,
                    'Y-m-d H:i:s:v'
                ])
            ],
        ],
    ],

];
