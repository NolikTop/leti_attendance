<?php

declare(strict_types=1);

namespace Noliktop\Leti\Command;

use Noliktop\Leti\Config\Config;
use Noliktop\Leti\LetiUser;
use Noliktop\Leti\LetiAttendance;
use Noliktop\Leti\LetiAuth;
use Noliktop\Leti\LetiUsersLoader;
use Noliktop\Leti\Log\Logger;
use Noliktop\Leti\Log\VkLog;
use Throwable;

class ReportAttendanceAction extends Action {

  public function execute(Config $config): void {
    foreach (LetiUsersLoader::load($config) as $api) {
      $attendance = new LetiAttendance($api);
      $class = $attendance->getActualClass();
      if ($class !== null) {
        $attendance->report($class->getId());
        $lessonName = $class->getLesson()->getTitle();
        $classId = $class->getId();
        $userName = $api->getFirstAndLastName();
        $subjectType = $class->getLesson()->getSubjectType();

        Logger::info("Reported {$lessonName}#{$classId}");
        VkLog::send("😎 Отметил {$userName} на паре {$subjectType} {$lessonName} #{$classId}");
      } else {
        Logger::info("No class to report attendance");
      }
    }

  }

}
