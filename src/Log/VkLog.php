<?php

declare(strict_types=1);

namespace Noliktop\Leti\Log;

use VK\Client\VKApiClient;

class VkLog {

  private static int $chatId;
  private static string $accessToken;

  private static VKApiClient $api;

  private static string $message = "";

  private static bool $enabled;

  public static function init(int $chatId, string $accessToken, bool $enabled): void {
    self::$chatId = $chatId;
    self::$accessToken = $accessToken;
    self::$api = new VKApiClient('5.200');
    self::$enabled = $enabled;
  }

  public static function send(string $message): void {
    self::$message .= "\n" . $message;
  }

  public static function sendImmediate(string $message): void {
    if (empty($message) || !self::$enabled) {
      return;
    }

    self::$api->messages()->send(self::$accessToken, [
      'message' => $message,
      'chat_id' => self::$chatId,
      'random_id' => 0
    ]);
  }

  public static function flush(): void {
    self::sendImmediate(self::$message);
    self::$message = "";
  }

}
