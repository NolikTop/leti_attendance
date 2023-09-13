<?php

declare(strict_types=1);

namespace Noliktop\Leti;

use DateTime;
use DateTimeZone;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Noliktop\Leti\Attendance\LetiClass;
use Noliktop\Leti\Log\Logger;
use Psr\Http\Message\ResponseInterface;

class LetiAttendance {

  private LetiUser $user;

  public function __construct(LetiUser $user) {
    if (!$user->isAuthorized()) {
      throw new LetiException("You need to authorize in LetiAuth");
    }

    $this->user = $user;
  }

  /**
   * @return LetiClass[]
   * @throws JsonException
   * @throws LetiException
   * @throws GuzzleException
   */
  public function getAll(): array {
    $api = $this->user->getClient();

    $response = $api->get('https://digital.etu.ru/attendance/api/schedule/check-in');
    $json = $this->checkResponseAndGetJson($response);

    return LetiClass::fromArrayArray($json);
  }

  public function getActualClass(): ?LetiClass {
    $now = $this->now();

    $classes = $this->getAll();
    foreach ($classes as $class) {
      $start = $class->getCheckInStart()->getTimestamp();
      $end = $class->getCheckInDeadline()->getTimestamp();
      if ($now >= $start && $now <= $end && !$class->getSelfReported()) {
        return $class;
      }
    }

    return null;
  }

  /**
   * @return LetiClass[]
   * @throws GuzzleException
   * @throws JsonException
   * @throws LetiException
   */
  public function getTodayClasses(): array {
    $startToday = $this->startOfToday();
    $endToday = $this->endOfToday();
    $result = [];

    $classes = $this->getAll();
    foreach ($classes as $class) {
      $start = $class->getCheckInStart()->getTimestamp();
      $end = $class->getCheckInDeadline()->getTimestamp();
      if ($start >= $startToday && $end <= $endToday) {
        $result[] = $class;
      }
    }

    return $result;
  }

  private function now(): int {
    return $this->nowObject()->getTimestamp();
  }

  private function startOfToday(): int {
    $nowDate = $this->nowObject();
    $nowDate->setTime(0, 0, 0);
    return $nowDate->getTimestamp();
  }

  private function endOfToday(): int {
    $nowDate = $this->nowObject();
    $nowDate->setTime(23, 59, 59);
    return $nowDate->getTimestamp();
  }

  private function nowObject(): DateTime {
    $timezone = new DateTimeZone('+03:00');
    return new DateTime('now', $timezone);
  }

  public function report(int $classId): void {
    $api = $this->user->getClient();

    $response = $api->post('https://digital.etu.ru/attendance/api/schedule/check-in/' . $classId);
    $json = $this->checkResponseAndGetJson($response);

    if ($json !== ['ok' => true]) {
      throw new LetiException("Invalid response");
    }
  }

  /**
   * @throws LetiException
   * @throws JsonException
   */
  private function checkResponseAndGetJson(ResponseInterface $response): array {
    $json = $response->getBody()->getContents();
    $statusCode = $response->getStatusCode();

    if ($statusCode !== 200 && $statusCode !== 201) {
      Logger::info($json);
      throw new LetiException("wrong status code {$statusCode}");
    }

    return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
  }

}
