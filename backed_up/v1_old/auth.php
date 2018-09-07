<?php
/*
login into NAMSAI system
POST: /v1/auth
PARAMETER:
  - email|username
  - password
RESPONSE:
  - access_token
*/
use \Firebase\JWT\JWT;
$app->post('/auth',function() use ($app,$config,$pdo){
  $email = $app->request->post("email");
  $username = $app->request->post("username");
  $password = $app->request->post("password");
  $passwordHasher = new Pentagonal\Phpass\PasswordHash(8,false);
  $authenFailed = false;
  if((!$email && !$username) || !$password){
    $authenFailed = true;
  }else{
    if($email){
      $query = $pdo->select()->from("user")->where("user_email","=",$email);
    }else{
      $query = $pdo->select()->from("user")->where("user_name","=",$username);
    }
    $result = $query->execute();
    if($result->rowCount()==0){
      $authenFailed = true;
    }else{
      $result = $result->fetch();
      $isMatch = $passwordHasher->checkPassword($password,$result["user_hash"]);
      if(!$isMatch){
        $authenFailed = true;
      }else{
        $token = array(
            "iss" => $config["jwt"]["domain"],
            "aud" => $config["jwt"]["domain"],
            "iat" => time(),
            "nbf" => time(),
            "exp" => time()+$config["jwt"]["expire"],
            "data" => array(
              "userId" => $result["user_id"],
            )
        );
        $jwt = JWT::encode($token, $config["jwt"]["secret"]);
        $app->render(200,array(
          'access_token' => $jwt,
        ));
      }
    }
  }
  if($authenFailed){
    $app->render(401,array(
      'error'=>array(
        'code' => 1,
        'message' => 'username,email or password isn\'t correct',
      )
    ));
  }
});
?>
