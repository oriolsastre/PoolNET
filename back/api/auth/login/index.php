<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use PoolNET\controller\AuthLogin;
use PoolNET\MW\Validator;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $body = Validator::parseBody([
    "usuari" => "string",
    "password" => "string",
  ]);
  AuthLogin::post($body);
} else {
  AuthLogin::respostaSimple(405);
}
