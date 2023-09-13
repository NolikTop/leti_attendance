<?php

declare(strict_types=1);

namespace Noliktop\Leti;

use GuzzleHttp\Client;

class LetiUser {

  private readonly Client $client;

  private bool $isAuthorized;

  private string $firstName;
  private string $lastName;

  public function __construct(array $config = []) {
    $defaults = [
      'cookies' => true,
      'verify' => false,
      'headers' => $this->defaultHeaders(),
      'http_errors' => false
    ];

    $this->client = new Client($config + $defaults);
  }

  private function defaultHeaders(): array {
    return [
      'sec-ch-ua' => '"Chromium";v="116", "Not)A;Brand";v="24", "Google Chrome";v="116"',
      'sec-ch-ua-mobile' => '?0',
      'sec-ch-ua-platform' => '"macOS"',
      'upgrade-insecure-requests' => '1',
      'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36',
    ];
  }

  public function getClient(): Client {
    return $this->client;
  }

  public function isAuthorized(): bool {
    return $this->isAuthorized;
  }

  public function getFirstName(): string {
    return $this->firstName;
  }

  public function getLastName(): string {
    return $this->lastName;
  }

  public function getFirstAndLastName(): string {
    return $this->firstName . " " . $this->lastName;
  }

  public function setIsAuthorized(bool $isAuthorized): void {
    $this->isAuthorized = $isAuthorized;
  }

  public function setFirstName(string $firstName): void {
    $this->firstName = $firstName;
  }

  public function setLastName(string $lastName): void {
    $this->lastName = $lastName;
  }

}
