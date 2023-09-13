<?php

declare(strict_types=1);

namespace Noliktop\Leti\Attendance;

use DateTime;
use Exception;

class LetiClass {

  private const FIELD_ID = 'id';
  private const FIELD_START = 'start';
  private const FIELD_END = 'end';
  private const FIELD_IS_DISTANT = 'isDistant';
  private const FIELD_ROOM = 'room';
  private const FIELD_LESSON = 'lesson';
  private const FIELD_TEACHERS = 'teachers';
  private const FIELD_SELF_REPORTED = 'selfReported';
  private const FIELD_GROUP_LEADER_REPORTED = 'groupLeaderReported';
  private const FIELD_TEACHER_REPORTED = 'teacherReported';
  private const FIELD_IS_GROUP_LEADER = 'isGroupLeader';
  private const FIELD_CHECK_IN_START = 'checkInStart';
  private const FIELD_CHECK_IN_DEADLINE = 'checkInDeadline';

  private int $id;
  private DateTime $start;
  private DateTime $end;
  private bool $isDistant;
  private ?string $room; // null если пара дистантная, аудитория, где пара будет
  private LetiLesson $lesson;
  /** @var LetiTeacher[] */
  private array $teachers;
  private ?bool $selfReported; // null - еще не отметил, true - отметил

  private ?bool $groupLeaderReported; // null - еще не отметил, false - не был, true - был
  private ?bool $teacherReported; // null - еще не отметил, false - не был, true - был
  private bool $isGroupLeader; // староста?
  private DateTime $checkInStart;
  private DateTime $checkInDeadline;

  /**
   * @throws Exception
   */
  public static function fromArray(array $data): self {
    $class = new self();
    $class->id = $data[self::FIELD_ID];
    $class->start = new DateTime($data[self::FIELD_START]);
    $class->end = new DateTime($data[self::FIELD_END]);
    $class->isDistant = $data[self::FIELD_IS_DISTANT];
    $class->room = $data[self::FIELD_ROOM];
    $class->lesson = LetiLesson::fromArray($data[self::FIELD_LESSON]);
    $class->teachers = LetiTeacher::fromArrayArray($data[self::FIELD_TEACHERS]);
    $class->selfReported = $data[self::FIELD_SELF_REPORTED];
    $class->groupLeaderReported = $data[self::FIELD_GROUP_LEADER_REPORTED];
    $class->teacherReported = $data[self::FIELD_TEACHER_REPORTED];
    $class->isGroupLeader = $data[self::FIELD_IS_GROUP_LEADER];
    $class->checkInStart = new DateTime($data[self::FIELD_CHECK_IN_START]);
    $class->checkInDeadline = new DateTime($data[self::FIELD_CHECK_IN_DEADLINE]);

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

  public function getStart(): DateTime {
    return $this->start;
  }

  public function getEnd(): DateTime {
    return $this->end;
  }

  public function isDistant(): bool {
    return $this->isDistant;
  }

  public function getRoom(): ?string {
    return $this->room;
  }

  public function getLesson(): LetiLesson {
    return $this->lesson;
  }

  /**
   * @return LetiTeacher[]
   */
  public function getTeachers(): array {
    return $this->teachers;
  }

  public function getSelfReported(): ?bool {
    return $this->selfReported;
  }

  public function getGroupLeaderReported(): ?bool {
    return $this->groupLeaderReported;
  }

  public function getTeacherReported(): ?bool {
    return $this->teacherReported;
  }

  public function isGroupLeader(): bool {
    return $this->isGroupLeader;
  }

  public function getCheckInStart(): DateTime {
    return $this->checkInStart;
  }

  public function getCheckInDeadline(): DateTime {
    return $this->checkInDeadline;
  }

  private function __construct() {}

}
