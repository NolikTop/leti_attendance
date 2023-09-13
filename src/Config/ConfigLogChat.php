<?php

declare(strict_types=1);

namespace Noliktop\Leti\Config;

class ConfigLogChat {

  private const FIELD_ENABLED = 'enabled';
  private const FIELD_CHAT_ID = 'chat_id';
  private const FIELD_ACCESS_TOKEN = 'access_token';

  private bool $enabled;

  private int $chat_id;

  private string $access_token;

  public static function fromArray(array $data): self {
    $class = new self();

    $class->enabled = $data[self::FIELD_ENABLED];
    $class->chat_id = $data[self::FIELD_CHAT_ID];
    $class->access_token = $data[self::FIELD_ACCESS_TOKEN];

    return $class;
  }
  public function isEnabled(): bool {
    return $this->enabled;
  }

  public function getChatId(): int {
    return $this->chat_id;
  }

  public function getAccessToken(): string {
    return $this->access_token;
  }

  private function __construct() {
  }

}
