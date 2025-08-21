<?php

namespace PoolNET\MW;

use PoolNET\interface\Middleware;
use PoolNET\interface\config\{Request, Response};

class LoginValidator extends Validator implements Middleware
{

    public function use(Request &$req, Response &$res): bool
    {
        return $this->requiredFields($req, $res, ["usuari" => "string", "password" => "string"]);
    }
}
