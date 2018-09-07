<?php
use \Firebase\JWT\JWT;
class Authen
{
  public function getToken($username,$password)
  {
    global $database;
    require(__DIR__.'/../config.php');
    $passwordHasher = new Pentagonal\Phpass\PasswordHash(8,false);
    $hash = $passwordHasher->hashPassword($password);
    if(filter_var($username, FILTER_VALIDATE_EMAIL)){
      $userData = array('email'=>$username);
    }else{
      $userData = array('username'=>$username);
    }
    $fields = array('id','hash');
    $userData = $database->get('user',$fields,$userData);
    if($userData){
      $isMatch = $passwordHasher->checkPassword($password,$userData['hash']);
      if($isMatch){
        $token = array(
            "iss" => $config["jwt"]["domain"],
            "aud" => $config["jwt"]["domain"],
            "iat" => time(),
            "nbf" => time(),
            "exp" => time()+$config["jwt"]["expire"],
            "data" => array(
              "userId" => $userData['id'],
            )
        );
        $jwt = JWT::encode($token, $config["jwt"]["secret"]);
        return $jwt;
      }
    }
    return null;
  }
  public function getId($token)
  {
    require(__DIR__.'/../config.php');
    try {
      $decoded = (array)JWT::decode($token, $config['jwt']['secret'], array('HS256'));
    } catch (Exception $e) {
      return null;
    }
    if(!isset($decoded['data'])){
      return null;
    }else{
      if($decoded['exp']<time()){
        return null;
      }else{
        return $decoded['data']->userId;
      }
    }
  }
}
?>
