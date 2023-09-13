<?php

declare(strict_types=1);

namespace Noliktop\Leti\Log;

use DateTime;

class Logger {

  private static bool $debug;

  public static function init(bool $debug): void {
    self::$debug = $debug;
  }

  public static function emergency(string $message): void {
    self::send($message, "EMERGENCY");
  }

  public static function alert(string $message): void {
    self::send($message, "ALERT");
  }

  public static function critical(string $message): void {
    self::send($message, "CRITICAL");
  }

  public static function error(string $message): void {
    self::send($message, "ERROR");
  }

  public static function warning(string $message): void {
    self::send($message, "WARNING");
  }

  public static function notice(string $message): void {
    self::send($message, "NOTICE");
  }

  public static function info(string $message): void {
    self::send($message, "INFO");
  }

  public static function debug(string $message): void {
    if (self::$debug) {
      self::send($message, "DEBUG");
    }
  }

  private static function send(string $message, string $prefix): void {
    /** @var DateTime|null $time */
    static $time = null;
    if($time === null){
      $time = new DateTime('now');
    }
    $time->setTimestamp(time());
    $formattedTime = $time->format("H:i:s");

    $message = "[{$formattedTime}] [{$prefix}]: {$message}";
    echo $message . PHP_EOL;
  }

}
