<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PoolNET\controller\Control;
use PoolNET\MW\AuthMW;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  Control::get();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
  AuthMW::rutaProtegida();
  Control::post();
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
  AuthMW::rutaProtegida();
  Control::delete();
} else {
  Control::respostaSimple(405);
}
