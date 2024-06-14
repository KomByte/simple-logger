<?php

declare(strict_types=1);

namespace SimpleLogger\streams;

use Throwable;

final class LogResult
{
    /**
     * @param string $level The log level
     * @param string $message The log message parsed by the logger
     * @param Throwable|null $exception Optional exception
     *
     *
     * ```php
     * $log = new LogResult(
     *   level: LogLevel::ALERT,
     *   message: 'This is an alert message',
     * );
     * ```
     */
    public function __construct(
        public string $level,
        public string $message,
        public ?Throwable $exception = null,
    ) {
    }
}
