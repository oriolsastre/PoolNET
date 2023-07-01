<?php
use PoolNET\config\Database, PoolNET\Accio;

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$database = new Database();
$dbcnx = $database->connect();

$accio = new Accio($dbcnx);

$result = $accio->read();

$num = $result->rowCount();
http_response_code(200);
if ($num > 0) {
  $accio_array = array();
  $accio_array['data'] = array();

  while($row = $result->fetch(PDO::FETCH_ASSOC)){
    extract($row);
    $accio_item = array(
      'id' => $accioID,
      'data_hora' => $data_hora,
      'ph' => $ph,
      'clor' => $clor,
      'antialga' => $antialga,
      'fluoculant' => $fluoculant,
      'aspirar' => $aspirar,
      'alcali' => $alcali,
      'aglutinant' => $aglutinant,
      'usuari' => array(
        'userID' => $userID,
        'usuari' => $usuari
      )
    );
    array_push($accio_array['data'], $accio_item);
  }

  // To JSON
  echo json_encode($accio_array);
} else {
  echo json_encode(
    array('message' => 'No s\'ha trobat cap accio')
  );
}
