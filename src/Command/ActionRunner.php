<?php

declare(strict_types=1);

namespace Noliktop\Leti\Command;

use Noliktop\Leti\Config\Config;
use Noliktop\Leti\Log\Logger;
use Noliktop\Leti\Log\VkLog;
use Throwable;

class ActionRunner {

  public static function run(string $action, Config $config): bool {
    switch ($action) {
      case "report":
        $act = new ReportAttendanceAction();
        break;
      case "info":
        $act = new InfoAction();
        break;
      default:
        return false;
    }

    try {
      $act->execute($config);
    } catch (Throwable $exception) {
      Logger::error($exception->__toString());
      VkLog::send("Произошла ошибка скрипта при действии {$action}, проверьте пожалуйста логи");
    }

    return true;
  }

}
