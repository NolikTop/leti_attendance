<?php

declare(strict_types=1);

namespace Noliktop\Leti\Config;

class ConfigLogger {

  private const FIELD_DEBUG = 'debug';

  private bool $debug;

  public static function fromArray(array $data): self {
    $class = new self();

    $class->debug = $data[self::FIELD_DEBUG];

    return $class;
  }

  public function isDebug(): bool {
    return $this->debug;
  }

  private function __construct(){
  }

}
