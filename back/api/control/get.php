<?php
use PoolNET\Control;
require_once __DIR__ . '/../../config/env.php';

// Headers
header('Access-Control-Allow-Origin: ' . getenv('ENV_HEADERS_ALLOW_ORIGIN'));
header('Content-Type: application/json');

try {
  //Result
  $result = Control::trobarMolts(['orderBy' => ['data_hora','DESC']],20);
  http_response_code(200);
  $num = count($result);
  if ($num > 0) {
    echo json_encode($result);
    return;
  } else {
    echo json_encode(
      array('message' => 'No s\'ha trobat cap control')
    );
    return;
  }
} catch (\Throwable $th) {
  http_response_code(500);
  echo json_encode(
    array('error' => $th->getMessage())
  );
  return;
}
