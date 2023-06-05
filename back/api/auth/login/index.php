<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With');

  include_once __DIR__ . '/../../../config/Database.php';
  include_once __DIR__ . '/../../../models/User.php';
  include_once __DIR__ . '/../../../models/JwtHandler.php';

  /* $db = new Database();
  $dbcnx = $db->connect(); */

  $user = new User();

  $data = json_decode(file_get_contents("php://input"));

  if (!isset($data->usuari) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode(
      array("message" => "Error amb les credencials.")
    );
    return;
  }

  $user->usuari = $data->usuari;
  if (!$user->getUserBy('usuari') || !$user->checkPswd($data->password)) {
    http_response_code(400);
    echo json_encode(
      array("message" => "Error amb les credencials.")
    );
    return;
  }

  $jwt = new JwtHandler();
  $token = $jwt->jwtEncodeData('piscina', array(
    'userID' => $user->userID,
    'usuari' => $user->usuari,
    'nivell' => $user->nivell,
    'email' => $user->getPrivateEmail()
  ));

  http_response_code(200);
  setcookie("token", $token, [
    "httpOnly" => true,
    "expires" => time() + (10 * 365 * 24 * 60 * 60), // 10 anys des d'ara
    "path" => "/", // Disponible en tot el lloc
    // "secure" => true, // Només disponible a través de HTTPS
    "samesite" => "Strict" // Només disponible per al mateix lloc (no cross-site)
  ]);
  echo json_encode(
    array("token" => $token)
  );
} else {
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  http_response_code(405);
  echo json_encode(
    array("message" => "Method not allowed")
  );
}
