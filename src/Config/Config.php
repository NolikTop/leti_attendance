<?php

declare(strict_types=1);

namespace Noliktop\Leti\Config;

use JsonException;

class Config {

  private const FIELD_LOGGER = 'logger';
  private const FIELD_LOG_CHAT = 'log_chat';
  private const FIELD_USERS = 'users';

  private ConfigLogger $logger;

  private ConfigLogChat $log_chat;

  /** @var ConfigUser[] */
  private array $users;

  public static function fromArray(array $data): self {
    $class = new self();

    $class->logger = ConfigLogger::fromArray($data[self::FIELD_LOGGER]);
    $class->log_chat = ConfigLogChat::fromArray($data[self::FIELD_LOG_CHAT]);
    $class->users = ConfigUser::fromArrayArray($data[self::FIELD_USERS]);

    return $class;
  }

  /**
   * @throws JsonException
   */
  public static function fromFile(string $path): self {
    $content = file_get_contents($path);

    return self::fromArray(json_decode($content, true, flags: JSON_THROW_ON_ERROR));
  }

  public function getLogger(): ConfigLogger {
    return $this->logger;
  }

  public function getLogChat(): ConfigLogChat {
    return $this->log_chat;
  }

  /**
   * @return ConfigUser[]
   */
  public function getUsers(): array {
    return $this->users;
  }

  private function __construct() {
  }

}
