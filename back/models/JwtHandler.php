<?php declare (strict_types = 1);
namespace PoolNET;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PoolNET\config\Env;
use stdClass;

class JwtHandler
{
  protected string $jwt_secrect;
  protected int $issuedAt;
  protected int $expire;

  public function __construct()
  {
    Env::executar();
    date_default_timezone_set('Europe/Berlin');
    $this->issuedAt = time();
    // Token Validity (3600 second = 1hr)
    $this->expire = $this->issuedAt + 3600;
    $this->jwt_secrect = (string) getenv('ENV_JWTSecret');
  }

  /**
   * Genera un JWT (JSON Web Token)
   * @param string $iss Identificador de l'emissor del token
   * @param array $data Dades a codificar al token
   * @return string JWT
   */
  public function jwtEncodeData(string $iss, array $data): string
  {
    $token = [
      //Adding the identifier to the token (who issued the token)
      "iss" => $iss,
      "aud" => $iss,
      "iat" => $this->issuedAt,
      "exp" => $this->expire,
      "data" => $data,
    ];
    return JWT::encode($token, $this->jwt_secrect, 'HS256');
  }

  /**
   * Decodifica un JWT
   * @param string $jwt_token JWT a decodificar
   * @return stdClass Les dades codificades al JWT
   */
  public function jwtDecodeData(string $jwt_token): stdClass
  {
    try {
      $decode = JWT::decode($jwt_token, new Key($this->jwt_secrect, 'HS256'));
      return $decode->data;
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
}
