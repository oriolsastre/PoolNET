<?php
use PoolNET\config\Database, PoolNET\Control, PoolNET\MW\AuthMW;

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With');

// Init DB & Connect
$database = new Database();
$dbcnx = $database->connect();

$authMW = new AuthMW($dbcnx);
$auth = $authMW->isValid();

if ($auth['success']) {
  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"), true);
  // Get user id from token
  $userID = $authMW->jwtDecodeData($_COOKIE['token'])->userID;

  // Validate
  try {
    $control = new Control($data);
    $control->usuari = $userID;
  } catch (\Throwable $th) {
    http_response_code(400);
    echo json_encode(
      array(
        "error" => $th->getMessage(),
      )
    );
    return;
  }

  if($control->allNull()){
    http_response_code(400);
    echo json_encode(
      array(
        "error" => "Mínim has d'omplir un camp.",
      )
    );
    return;
  }

  $controlDesat = $control->desar();
  if ($controlDesat) {
    http_response_code(204);
    return;
  } else {
    http_response_code(500);
    echo json_encode(
      array("error" => "No s'ha pogut crear el control de l'aigua.")
    );
    return;
  }
} else {
  http_response_code(401);
  echo json_encode(
    array('message' => $auth['message'])
  );
  return;
}