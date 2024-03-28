<?php

declare (strict_types=1);

namespace SimpleLogger\streams;

class CollectionStream implements LogStream
{
    /**
     * @param array<LogStream> $streams
     */
    public function __construct(
        private array $streams,
    ) {
    }

    public function write(LogResult $log): void
    {
        foreach ($this->streams as $stream) {
            $stream->write($log);
        }
    }
}
