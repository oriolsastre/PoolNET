<?php
namespace PoolNET\config;

use PoolNET\error\InvalidJwtToken;
use PoolNET\service\JwtHandler;
use stdClass;

class Session
{
  private JwtHandler $jwtHandler;
  private ?string $tokenCookie;
  private ?stdClass $tokenData;
  public bool $loggedInUser = false;
  public function __construct()
  {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
    $this->jwtHandler = new JwtHandler();
    $this->tokenCookie = $_COOKIE['token'] ?? null;

  }

  private function getTokenData()
  {
    try {
      $this->tokenData = $this->jwtHandler->jwtDecodeData($this->tokenCookie);
      $this->loggedInUser = true;
    } catch (InvalidJwtToken $err) {
      $this->tokenData = null;
      $this->loggedInUser = false;
    }
  }
  private function setUser()
  {

  }
}
