<?php

declare(strict_types=1);

namespace Noliktop\Leti\Command;

use Noliktop\Leti\Config\Config;

abstract class Action {

  abstract public function execute(Config $config): void;

}
