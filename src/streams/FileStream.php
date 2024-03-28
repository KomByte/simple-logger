<?php

declare (strict_types=1);

namespace SimpleLogger\streams;

use SimpleLogger\data\{AsyncFileWriter, FileWriter, SyncFileWriter};

/**
 * Write content to a file
 */
class FileStream implements LogStream
{
    public static function async(string $filePath)
    {
        return new self(new AsyncFileWriter($filePath));
    }

    public static function sync(string $filePath)
    {
        return new self(new SyncFileWriter($filePath));
    }

    public function __construct(
        private FileWriter $writer,
    ) {
    }

    public function write(LogResult $log): void
    {
        $this->writer->write($log->getMessage());
    }
}
