<?php

declare (strict_types=1);

use SimpleLogger\Logger;
use SimpleLogger\streams\{CollectionStream, FileStream, StdoutStream};

require __DIR__ . '/vendor/autoload.php';

$logger = new Logger(stream: new CollectionStream([
    new StdoutStream(),
    FileStream::async(__DIR__ . '/log.log'),
]));

$logger->info('This is an info message', ['exception' => new Exception('This is an exception')]);
$logger->warning('This is a warning message');
$logger->debug('This is a debug message with {msg}', ['msg' => 'parameters']);
