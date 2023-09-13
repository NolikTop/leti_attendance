<?php

declare(strict_types=1);

namespace Noliktop\Leti\Config;

class ConfigUser {

  private const FIELD_NAME = 'name';
  private const FIELD_EMAIL = 'email';
  private const FIELD_PASSWORD = 'password';

  private string $name;
  private string $email;
  private string $password;

  public static function fromArray(array $data): self {
    $class = new self();

    $class->name = $data[self::FIELD_NAME];
    $class->email = $data[self::FIELD_EMAIL];
    $class->password = $data[self::FIELD_PASSWORD];

    return $class;
  }

  /**
   * @return self[]
   */
  public static function fromArrayArray(array $dataArray): array {
    $result = [];
    foreach ($dataArray as $data) {
      $result[] = self::fromArray($data);
    }

    return $result;
  }

  public function getName(): string {
    return $this->name;
  }

  public function getEmail(): string {
    return $this->email;
  }

  public function getPassword(): string {
    return $this->password;
  }

  private function __construct(){
  }

}
