<?php

use Noliktop\Leti\Command\ActionRunner;
use Noliktop\Leti\Config\Config;
use Noliktop\Leti\Log\Logger;
use Noliktop\Leti\Log\VkLog;

require_once 'vendor/autoload.php';

$path = __DIR__ . DIRECTORY_SEPARATOR . 'config.json';
$config = Config::fromFile($path);

$configLogger = $config->getLogger();
$debug = $configLogger->isDebug();
Logger::init($debug);

$mode = $debug ? "DEBUG" : "PRODUCTION";
Logger::info("Running in {$mode} mode");

$configLogChat = $config->getLogChat();
VkLog::init($configLogChat->getChatId(), $configLogChat->getAccessToken(), $configLogChat->isEnabled());

if (!isset($argv[1])) {
  Logger::error("No action passed. Use php run.php <action>");
  return;
}

if (!ActionRunner::run($argv[1], $config)) {
  Logger::error("Action not found");
}

VkLog::flush();
