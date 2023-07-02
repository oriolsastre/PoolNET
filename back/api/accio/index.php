<?php

use PoolNET\config\Database;
use PoolNET\MW\AuthMW;

$database = new Database();
$dbcnx = $database->connect();
$headers = getallheaders();
$authMW = new AuthMW($dbcnx, $headers);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  require_once 'get.php';
} else {
  http_response_code(405);
  echo json_encode(
    array('message' => 'Method not allowed')
  );
}
