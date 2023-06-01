<?php
require __DIR__ . '/../../middlewares/AuthMW.php';
require __DIR__ . '/../../config/database.php';

$database = new Database();
$dbcnx = $database->connect();
$headers = getallheaders();
$authMW = new Auth($dbcnx, $headers);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  require_once 'get.php';
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $auth = $authMW->isValid();
  if ($auth['success']) {
    require_once 'create.php';
  } else {
    http_response_code(401);
    echo json_encode(
      array('message' => $auth['message'])
    );
  }
} else {
  http_response_code(405);
  echo json_encode(
    array('message' => 'Method not allowed')
  );
}
