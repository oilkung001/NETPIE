<?php
/*
get access_token
POST: /v1/auth
PARAMETER:
  - user (username or email)
  - password
RESPONSE:
  - access_token
*/
$app->post('/auth',function() use ($app){
  $username = $app->request->post("user");
  $password = $app->request->post("password");
  if(!isset($username) || !isset($password)){
    $app->render(400,ErrorCode::get(15));
  }
  $token = Authen::getToken($username,$password);
  if(is_null($token)){
    $app->render(400,ErrorCode::get(16));
  }
  $app->render(200,array(
    'access_token'=>$token
  ));
});
?>
