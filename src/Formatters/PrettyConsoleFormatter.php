<?php

declare(strict_types=1);

namespace SimpleLogger\Formatters;

use PHP_Parallel_Lint\PhpConsoleColor\ConsoleColor;
use Psr\Log\LogLevel;
use RuntimeException;
use SimpleLogger\streams\LogResult;

use function sprintf;
use function strlen;
use function strtolower;
use function strtoupper;

/**
 * Format log messages with colors and styles for the console
 */
class PrettyConsoleFormatter extends DefaultConsoleFormatter
{
    protected static $format = '%s %s %s' . PHP_EOL;

    private ConsoleColor $consoleColor;
    private ?DefaultFormatter $defaultFormatter;

    public function __construct()
    {
        if (class_exists(ConsoleColor::class) === false) {
            throw new RuntimeException('Please install php-parallel-lint/php-console-color to use this formatter');
        }

        $this->consoleColor = new ConsoleColor();
    }

    /**
     * Return `true` if colored output are supported
     */
    private function isSupported(): bool
    {
        static $supported = null;
        if ($supported === null) {
            $supported = $this->consoleColor->are256ColorsSupported();
            $this->defaultFormatter = $supported ? null : new DefaultFormatter();
        }

        return $supported;
    }

    public function format(LogResult $result): string
    {
        // If colored output are not supported, use the default formatter
        if ($this->isSupported() === false) {
            return $this->defaultFormatter->format($result);
        }

        // Format with bold and dark styles
        if ($this->consoleColor->isSupported()) {
            return $this->formatWithStyles($result);
        }

        return $this->formatOnlyWithColors($result);
    }

    private function formatWithStyles(LogResult $result): string
    {
        $color = $this->consoleColor;
        $currentDate = $this->getCurrentDate();

        $message = sprintf(
            static::$format,
            $color->apply('dark', "[$currentDate]"),
            $color->apply('bold', $this->colorizeLevel($result->level)),
            $result->message,
        );

        return $this->withExceptionMessage($result, $message, strlen($currentDate));
    }

    private function formatOnlyWithColors(LogResult $logResult): string
    {
        $currentDate = $this->getCurrentDate();
        $message     = sprintf(
            static::$format,
            '[' . $currentDate . ']',
            $this->colorizeLevel($logResult->level),
            $logResult->message,
        );

        return $this->withExceptionMessage($logResult, $message, strlen($currentDate));
    }

    private function colorizeLevel(string $level): string
    {
        $color = $this->consoleColor;
        $level = strtoupper($level);

        // Colors from \Amp\Log\ConsoleFormatter
        return match (strtolower($level)) {
            LogLevel::DEBUG => $color->apply('cyan', $level),
            LogLevel::INFO => $color->apply('blue', $level),
            LogLevel::NOTICE => $color->apply('green', $level),
            LogLevel::WARNING => $color->apply('yellow', $level),
            LogLevel::ERROR => $color->apply('red', $level),
            LogLevel::CRITICAL => $color->apply('bg_red', $level),
            LogLevel::ALERT => $color->apply('bg_magenta', $level),
            LogLevel::EMERGENCY => $color->apply('bg_yellow', $level),
            default => $level,
        };
    }
}
