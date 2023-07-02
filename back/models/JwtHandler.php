<?php
namespace PoolNET;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PoolNET\config\Env;

class JwtHandler
{
  protected $jwt_secrect;
  protected $token;
  protected $issuedAt;
  protected $expire;
  protected $jwt;

  public function __construct()
  {
    Env::executar();
    // set your default time-zone
    date_default_timezone_set('Europe/Berlin');
    $this->issuedAt = time();

    // Token Validity (3600 second = 1hr)
    $this->expire = $this->issuedAt + 3600;

    // Set your secret or signature
    $this->jwt_secrect = getenv('ENV_JWTSecret');
  }

  public function jwtEncodeData($iss, $data)
  {

    $this->token = array(
      //Adding the identifier to the token (who issue the token)
      "iss" => $iss,
      "aud" => $iss,
      // Adding the current timestamp to the token, for identifying that when the token was issued.
      "iat" => $this->issuedAt,
      // Token expiration
      "exp" => $this->expire,
      // Payload
      "data" => $data,
    );

    $this->jwt = JWT::encode($this->token, $this->jwt_secrect, 'HS256');
    return $this->jwt;
  }

  public function jwtDecodeData($jwt_token)
  {
    try {
      $decode = JWT::decode($jwt_token, new Key($this->jwt_secrect, 'HS256'));
      return $decode->data;
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
}
