<?php

namespace App\Core\Log;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class LogDailyFormatter
{
    /**
     * Customize the given logger instance.
     *
     * @param Logger $logger
     * @return void
     */
    public function __invoke(Logger $logger): void
    {
        foreach ($logger->getHandlers() as $handler) {
            // Check if handler is RotatingFileHandler
            if ($handler instanceof RotatingFileHandler) {
                // Custom file log name
                $currentDate = now()->format('Y-m-d');
                $handler->setFilenameFormat("daily_log_{$currentDate}", 'Y-m-d');
            }
        }
    }
}
