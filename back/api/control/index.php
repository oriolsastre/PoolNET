<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  require_once 'get.php';
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
  require_once 'create.php';
} else {
  http_response_code(405);
  echo json_encode(
    array('message' => 'Method not allowed')
  );
}
