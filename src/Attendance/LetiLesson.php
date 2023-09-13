<?php

declare(strict_types=1);

namespace Noliktop\Leti\Attendance;

class LetiLesson {

  private const FIELD_ID = 'id';
  private const FIELD_TITLE = 'title';
  private const FIELD_SHORT_TITLE = 'shortTitle';
  private const FIELD_SUBJECT_TYPE = 'subjectType';

  private int $id;
  private string $title;
  private string $shortTitle;

  private string $subjectType;

  public static function fromArray(array $data): self {
    $class = new self();

    $class->id = $data[self::FIELD_ID];
    $class->title = $data[self::FIELD_TITLE];
    $class->shortTitle = $data[self::FIELD_SHORT_TITLE];
    $class->subjectType = $data[self::FIELD_SUBJECT_TYPE];

    return $class;
  }

  public function getId(): int {
    return $this->id;
  }

  public function getTitle(): string {
    return $this->title;
  }

  public function getShortTitle(): string {
    return $this->shortTitle;
  }

  public function getSubjectType(): string {
    return $this->subjectType;
  }

  private function __construct() {}

}
