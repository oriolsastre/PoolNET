<?php
// Copia aquest fitxer, anomena'l Env.php i emplena les dades amb les del teu entorn.
namespace PoolNET\config;

class Env
{
  /**
   * Aplica les variables d'entorn
   * @return void S'han aplicat les variables i es poden recuperar amb ``getenv()``
   */
  public static function executar(): void
  {
    putenv('ENV_DB_NAME=PoolNET');

    putenv('ENV_HEADERS_ALLOW_ORIGIN=*');
    putenv('ENV_HEADERS_ALLOW_HEADERS=*');

    putenv('ENV_JWTSecret=YourSecretJwtSecret');
    putenv('ENV_ServerSalt=YourSecretServerSalt');
  }
}
