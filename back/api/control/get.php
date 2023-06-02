<?php
require_once '../../models/Control.php';

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//Result
$result = Control::trobarTots();

if ($result["success"]) {
  http_response_code(200);
  $num = count($result);
  if ($num > 0) {
    echo json_encode($result["data"]);
    return;
  } else {
    echo json_encode(
      array('message' => 'No s\'ha trobat cap control')
    );
    return;
  }
} else {
  http_response_code(500);
  echo json_encode(
    array('error' => 'Error obtenint les dades.')
  );
  return;
}
