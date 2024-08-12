<?php

namespace PoolNET\MW;

use PoolNET\config\{Request, Response};
use PoolNET\interface\Middleware;

class LoginValidator extends Validator implements Middleware
{

    public function use(Request &$req, Response &$res): bool
    {
        return self::requiredFields($req, $res, ["usuari" => "string", "password" => "string"]);
    }
}
