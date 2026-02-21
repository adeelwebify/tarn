<?php

namespace Tarn\Core;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Log {
    protected static ?Logger $logger = null;

    /**
     * Initialize the Monolog logger.
     *
     * @param string $logPath
     * @return void
     */
    public static function init(string $logPath): void {
        self::$logger = new Logger('TarnApp');
        
        // Ensure the log directory exists
        $logDir = dirname($logPath);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        self::$logger->pushHandler(new StreamHandler($logPath, Logger::DEBUG));
    }

    /**
     * Log an info message.
     *
     * @param string $message
     * @param array $context
     */
    public static function info(string $message, array $context = []): void {
        self::$logger?->info($message, $context);
    }

    /**
     * Log an error message.
     *
     * @param string $message
     * @param array $context
     */
    public static function error(string $message, array $context = []): void {
        self::$logger?->error($message, $context);
    }
    
    /**
     * Log a debug message.
     *
     * @param string $message
     * @param array $context
     */
    public static function debug(string $message, array $context = []): void {
        self::$logger?->debug($message, $context);
    }
}
