<?php

namespace Utils;

class Logger
{
    private static bool $enableDebug = true;

    public static function log(string $message, string $level = 'INFO'): void
    {
        echo "[" . strtoupper($level) . "] " . $message . PHP_EOL;
    }

    public static function info(string $message): void
    {
        self::log($message, 'INFO');
    }

    public static function debug(string $message): void
    {
        if (self::$enableDebug) {
            self::log($message, 'DEBUG');
        }
    }

    public static function error(string $message): void
    {
        self::log($message, 'ERROR');
    }

    public static function setDebug(bool $enable): void
    {
        self::$enableDebug = $enable;
    }
}
