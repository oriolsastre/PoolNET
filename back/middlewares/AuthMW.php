<?php
require './models/JwtHandler.php';
require './models/User.php';

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
    if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {
      $data = $this->jwtDecodeData($matches[1]);
      if(isset($data['data']->userID)){
        $user = new User($this->db);
        $user->userID = $data['data']->userID;
        if($user->getUserById()){
          return [
            "success" => true,
          ];
        }
      }
      return [
        "success" => false,
        "message" => $data['message'],
      ];
    } else {
      return [
        "success" => false,
        "message" => "Token not found",
      ];
    }
  }
}
