<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PoolNET\controller\Control;
use PoolNET\MW\AuthMW;
use PoolNET\MW\Validator;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  Control::get();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
  AuthMW::rutaProtegida();
  $body = Validator::parseBody();
  Validator::validateBodyWithClass($body, 'PoolNET\Control');
  Control::post($body);
} elseif ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
  AuthMW::rutaProtegida();
  $body = Validator::parseBody(array('controlID' => "integer"));
  Validator::validateBodyWithClass($body, 'PoolNET\Control');
  Control::patch($body);
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
  AuthMW::rutaProtegida();
  $body = Validator::parseBody(array('controlID' => "integer"));
  Validator::validateBodyWithClass($body, 'PoolNET\Control');
  Control::delete($body);
} else {
  Control::respostaSimple(405);
}
