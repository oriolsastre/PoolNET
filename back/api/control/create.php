<?php
include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../middlewares/AuthMW.php';
include_once __DIR__ . '/../../models/Control.php';

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With');

// Init DB & Connect
$database = new Database();
$dbcnx = $database->connect();

$authMW = new Auth($dbcnx);
$auth = $authMW->isValid();

if ($auth['success']) {
  // Init Control Object
  $control = new Control();
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
  if ($controlDesat['success']) {
    http_response_code(201);
    echo json_encode(
      array(
        "message" => "Control de l'aigua creat.",
        "data" => $control,
      )
    );
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