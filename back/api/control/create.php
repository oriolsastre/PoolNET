<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With');



include_once '../../config/Database.php';
include_once '../../models/Control.php';

// Init DB & Connect
$database = new Database();
$dbcnx = $database->connect();

// Init Control Object
$control = new Control($dbcnx);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

$control->ph = $data->ph;
$control->clor = $data->clor;
$control->alcali = $data->alcali;
$control->transparent = $data->transparent;
$control->temperatura = $data->temperatura;
$control->fons = $data->fons;
$control->usuari = $data->usuari;

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