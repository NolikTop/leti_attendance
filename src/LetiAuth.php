<?php

declare(strict_types=1);

namespace Noliktop\Leti;

use GuzzleHttp\Exception\GuzzleException;
use Noliktop\Leti\Log\Logger;
use Psr\Http\Message\ResponseInterface;

class LetiAuth {

  private LetiUser $user;
  private string $email;
  private string $password;

  private string $token;
  private string $state;
  private string $clientId;
  private string $authToken;

  public function __construct(LetiUser $user, string $email, string $password) {
    $this->user = $user;
    $this->email = $email;
    $this->password = $password;
  }

  public function processAuthorization(): void {
    Logger::info("Starting authorization");

    $this->loadLoginPage();
    Logger::info("Loaded login page");

    $this->logInLeti();
    Logger::info("Successfully logged in for user " . $this->user->getFirstAndLastName());

    $this->logInAttendance();
    Logger::info("Successfully logged in attendance");
  }

  private function loadLoginPage(): void {
    $api = $this->user->getClient();

    $response = $api->get('https://lk.etu.ru/oauth/authorize', [
      'query' => [
        'response_type' => 'code',
        'client_id' => 29,
        'redirect_uri' => 'https://digital.etu.ru/attendance/api/auth/redirect',
      ],
      'headers' => [
        'cache-control' => 'max-age=0',
        'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7'
      ],
    ]);

    $html = $this->checkResponseAndGetHtml($response);

    $token = $this->getInputValue("_token", $html);
    $this->token = $token;

    Logger::debug("_token={$token}");
  }

  /**
   * @throws GuzzleException
   * @throws LetiException
   */
  private function logInLeti(): void {
    $api = $this->user->getClient();

    $response = $api->post('https://lk.etu.ru/login', [
      'form_params' => [
        '_token' => $this->token,
        'email' => $this->email,
        'password' => $this->password
      ]
    ]);

    $html = $this->checkResponseAndGetHtml($response);

    $formHtml = $this->find('<!-- Authorize Button -->', '</form>', $html);

    $token = $this->getInputValue('_token', $formHtml);
    $state = $this->getInputValue('state', $formHtml, true);
    $client_id = $this->getInputValue('client_id', $formHtml);
    $auth_token = $this->getInputValue('auth_token', $formHtml);

    Logger::debug("_token={$token}");
    Logger::debug("state={$state}");
    Logger::debug("client_id={$client_id}");
    Logger::debug("auth_token={$auth_token}");

    $name = $this->find('<h6 class="mb-0">', '</h6>', $html);

    [$lastName, $firstName] = explode(" ", $name);
    $this->user->setFirstName($firstName);
    $this->user->setLastName($lastName);

    $this->token = $token;
    $this->state = $state;
    $this->clientId = $client_id;
    $this->authToken = $auth_token;
  }

  /**
   * @throws GuzzleException
   * @throws LetiException
   */
  private function logInAttendance(): void {
    $api = $this->user->getClient();

    $response = $api->post('https://lk.etu.ru/oauth/authorize', [
      'form_params' => [
        '_token' => $this->token,
        'state' => $this->state,
        'client_id' => $this->clientId,
        'auth_token' => $this->authToken
      ]
    ]);

    $this->checkResponseAndGetHtml($response);

    $this->user->setIsAuthorized(true);
  }

  private function getInputValue(string $name, string $html, bool $canBeEmpty = false): string {
    return $this->find('<input type="hidden" name="' . $name. '" value="', '"', $html, $canBeEmpty);
  }

  private function find(string $start, string $end, string $html, bool $canBeEmpty = false): string {
    $data = explode($start, $html, 2);
    if (count($data) < 2) {
      throw new LetiException("Start {$start} cant be found");
    }

    if (empty($end)) {
      return $data[1];
    }

    [$value] = explode($end, $data[1], 2);
    if (!$canBeEmpty && empty($value)) {
      throw new LetiException("empty value");
    }

    return $value;
  }

  private function checkResponseAndGetHtml(ResponseInterface $response): string {
    $html = $response->getBody()->getContents();
    $statusCode = $response->getStatusCode();
    if ($statusCode !== 200) {
      Logger::info($html);
      throw new LetiException("wrong status code {$statusCode}");
    }

    return $html;
  }


}
