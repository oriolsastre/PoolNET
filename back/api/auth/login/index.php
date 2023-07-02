<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use PoolNET\controller\AuthLogin;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  AuthLogin::post();
} else {
  AuthLogin::respostaSimple(405);
}
