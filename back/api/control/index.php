<?php

require __DIR__ . '/../../middlewares/AuthMW.php';
require __DIR__ . '/../../config/database.php';

$database = new Database();
$dbcnx = $database->connect();
$headers = getallheaders();
$authMW = new Auth($dbcnx, $headers);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $auth = $authMW->isValid();
  if ($auth['success']) {
    require_once 'get.php';
  } else {
    http_response_code(401);
    echo json_encode(
      array('message' => $auth['message'])
    );
  }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
  require_once 'create.php';
}
