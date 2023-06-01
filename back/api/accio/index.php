<?php
require_once __DIR__ . '/../../middlewares/AuthMW.php';
require_once __DIR__ . '/../../config/database.php';

$database = new Database();
$dbcnx = $database->connect();
$headers = getallheaders();
$authMW = new Auth($dbcnx, $headers);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  require_once 'get.php';
} else {
  http_response_code(405);
  echo json_encode(
    array('message' => 'Method not allowed')
  );
}