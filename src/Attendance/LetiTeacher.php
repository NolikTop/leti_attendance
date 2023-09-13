<?php

declare(strict_types=1);

namespace Noliktop\Leti\Attendance;

class LetiTeacher {

  private const FIELD_ID = 'id';
  private const FIELD_SURNAME = 'surname';
  private const FIELD_NAME = 'name';
  private const FIELD_MIDNAME = 'midname';

  private int $id;
  private string $surname;
  private string $name;
  private string $midname;

  public static function fromArray(array $data): self {
    $class = new self();

    $class->id = $data[self::FIELD_ID];
    $class->surname = $data[self::FIELD_SURNAME];
    $class->name = $data[self::FIELD_NAME];
    $class->midname = $data[self::FIELD_MIDNAME];

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

  public function getId(): int {
    return $this->id;
  }

  public function getSurname(): string {
    return $this->surname;
  }

  public function getName(): string {
    return $this->name;
  }

  public function getMidname(): string {
    return $this->midname;
  }

  private function __construct() {}

}
