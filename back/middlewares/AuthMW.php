<?php
require __DIR__ . '/../models/JwtHandler.php';
require __DIR__ . '/../models/User.php';

class Auth extends JwtHandler
{
  protected $db;
  protected $headers;
  protected $token;

  public function __construct($db)
  {
    parent::__construct();
    $this->db = $db;
  }

  public function isValid()
  {
    if (isset($_COOKIE['token'])){
      $data = $this->jwtDecodeData($_COOKIE['token']);
      if(isset($data->userID)){
        $user = User::trobarPerId($data->userID);
        if($user!=null){
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
