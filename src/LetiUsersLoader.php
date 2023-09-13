<?php

declare(strict_types=1);

namespace Noliktop\Leti;

use Noliktop\Leti\Config\Config;
use Noliktop\Leti\Log\Logger;
use Throwable;

class LetiUsersLoader {

  /**
   * @param Config $config
   * @return LetiUser[]
   */
  public static function load(Config $config): array {
    $users = [];

    foreach ($config->getUsers() as $configUser) {
      Logger::info("Loading user {$configUser->getName()}");
      $user = new LetiUser();
      $auth = new LetiAuth($user, $configUser->getEmail(), $configUser->getPassword());
      $auth->processAuthorization();

      $users[] = $user;
    }

    return $users;
  }

}
