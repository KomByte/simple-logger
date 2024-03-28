<?php

declare (strict_types=1);

namespace SimpleLogger\streams;

use Psr\Log\LogLevel;
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

    /**
     * Get the log messages with this format:
     * [TIME] [LEVEL] MESSAGE
     * STACKTRACE
     */
    public function getMessage(): string
    {
        $message = sprintf(
            '[%s] [%s] %s' . PHP_EOL,
            date('Y-m-d H:i:s'),
            strtoupper($this->level),
            $this->message,
        );

        if ($this->exception !== null) {
            $message .= $this->getExceptionMessage();
        }

        return $message;
    }

    private function getExceptionMessage(): string
    {
        return 'Caused by: ' . $this->exception::class . ' (' . $this->exception->getMessage() . ') '
        . PHP_EOL . $this->exception->getTraceAsString() . PHP_EOL;
    }
}
