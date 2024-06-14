<?php

declare(strict_types=1);

namespace SimpleLogger\streams;

use SimpleLogger\Formatters\{Formatter, PrettyConsoleFormatter};

/**
 * Write log messages to the standard output.
 */
class StdoutStream implements LogStream
{
    public function __construct(
        private Formatter $formatter = new PrettyConsoleFormatter(),
    ) {
    }
    public function write(LogResult $log): void
    {
        print($this->formatter->format($log));
    }
}
