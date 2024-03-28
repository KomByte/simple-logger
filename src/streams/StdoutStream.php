<?php

declare (strict_types=1);

namespace SimpleLogger\streams;

/**
 * Write log messages to the standard output.
 */
class StdoutStream implements LogStream
{
    public function write(LogResult $log): void
    {
        print($log->getMessage());
    }
}
