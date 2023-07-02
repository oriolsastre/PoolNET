<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PoolNET\controller\Control;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  Control::get();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
  Control::post();
} else {
  Control::respostaSimple(405);
}
