<?php

declare(strict_types=1);

namespace Noliktop\Leti\Command;

use Noliktop\Leti\Config\Config;
use Noliktop\Leti\LetiAttendance;
use Noliktop\Leti\LetiUsersLoader;
use Noliktop\Leti\Log\VkLog;

class InfoAction extends Action {

  public function execute(Config $config): void {
    foreach (LetiUsersLoader::load($config) as $user) {
      $attendance = new LetiAttendance($user);
      $classes = $attendance->getTodayClasses();
      if (!empty($classes)) {
        $userName = $user->getFirstAndLastName();
        VkLog::send("🥃 Отметки на парах у {$userName}");
        VkLog::send("Сам | Староста | Препод");
        foreach ($classes as $class) {
          $lesson = $class->getLesson();
          $subjectType = $lesson->getSubjectType();
          $title = $lesson->getShortTitle();

          $self = $this->emoji($class->getSelfReported());
          $starosta = $this->emoji($class->getGroupLeaderReported());
          $teacher = $this->emoji($class->getTeacherReported());
          VkLog::send("{$self} {$starosta} {$teacher} {$subjectType} {$title}");
        }
        VkLog::send("");
      }
    }
  }

  private function emoji(?bool $status): string {
    if ($status === null) {
      return "🚬";
    }

    return $status ? "✅" : "❌";
  }

}
