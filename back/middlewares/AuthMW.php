<?php
require __DIR__ . '/../models/JwtHandler.php';
require __DIR__ . '/../models/User.php';

class Auth extends JwtHandler
{
  protected $db;
  protected $headers;
  protected $token;

  public function __construct($db, $headers)
  {
    parent::__construct();
    $this->db = $db;
    $this->headers = $headers;
  }

  public function isValid()
  {
    //if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {
    if (isset($_COOKIE['token'])){
      // $data = $this->jwtDecodeData($matches[1]);
      $data = $this->jwtDecodeData($_COOKIE['token']);
      if(isset($data->userID)){
        $user = new User($this->db);
        $user->userID = $data->userID;
        if($user->getUserById()){
          return [
            "success" => true,
          ];
        }
      }
      return [
        "success" => false,
        "message" => $data,
      ];
    } else {
      return [
        "success" => false,
        "message" => "Token not found",
      ];
    }
  }
}
