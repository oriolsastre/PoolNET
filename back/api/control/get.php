<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Control.php';

// Init DB & Connect
$database = new Database();
$dbcnx = $database->connect();

// Init Control Object
$control = new Control($dbcnx);

//Result
$result = $control->read();

$num = $result->rowCount();
http_response_code(200);
if ($num > 0) {
  $control_array = array();
  $control_array['data'] = array();

  while($row = $result->fetch(PDO::FETCH_ASSOC)){
    extract($row);
    $control_item = array(
      'id' => $controlID,
      'data_hora' => $data_hora,
      'pH' => $ph,
      'clor' => $clor,
      'alcali' => $alcali,
      'temperatura' => $temperatura,
      'transparent' => $transparent,
      'fons' => $fons,
      'usuari' => $usuari
    );
    array_push($control_array['data'], $control_item);
  }

  // To JSON
  echo json_encode($control_array);
} else {
  echo json_encode(
    array('message' => 'No s\'ha trobat cap control')
  );
}
