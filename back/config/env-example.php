<?php
// Copia aquest fitxer, anomena'l Env.php i emplena les dades amb les del teu entorn.
namespace PoolNET\config;

class Env
{
  public static function executar()
  {
    putenv('ENV_DB_HOST=localhost');
    putenv('ENV_DB_NAME=PoolNET');
    putenv('ENV_DB_USER=root');
    putenv('ENV_DB_PSWD=1234');

    putenv('ENV_HEADERS_ALLOW_ORIGIN=*');
    putenv('ENV_HEADERS_ALLOW_HEADERS=*');

    putenv('ENV_JWTSecret=YourSecretJwtSecret');
    putenv('ENV_ServerSalt=YourSecretServerSalt');
  }
}
