<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Control.php';
include_once '../../models/JwtHandler.php';

// Init DB & Connect
$database = new Database();
$dbcnx = $database->connect();

// Init Control Object
$control = new Control($dbcnx);

// Init JWT Handler
$jwt = new JwtHandler($dbcnx);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));
// Get user id from token
$userID = $jwt->jwtDecodeData($_COOKIE['token'])->userID;

if(isset($data->ph)) $control->ph = $data->ph; else $control->ph = null;
if(isset($data->clor)) $control->clor = $data->clor; else $control->clor = null;
if(isset($data->alcali)) $control->alcali = $data->alcali; else $control->alcali = null;
if(isset($data->transparent)) $control->transparent = $data->transparent; else $control->transparent = null;
if(isset($data->temperatura)) $control->temperatura = $data->temperatura; else $control->temperatura = null;
if(isset($data->fons)) $control->fons = $data->fons; else $control->fons = null;
$control->usuari = $userID;

if($control->create()){
  http_response_code(201);
  echo json_encode(
    array("message" => "Control de l'aigua creat.")
  );
} else {
  http_response_code(500);
  echo json_encode(
    array("error" => "No s'ha pogut crear el control de l'aigua.")
  );
}