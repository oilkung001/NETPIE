<?php
/*
Register new user
POST: /v1/users
PARAMETER:
  - name
  - email
  - username
  - password
RESPONSE:
  - id
*/
$app->post('/users',function() use ($app){
  $email = $app->request->post("email");
  $password = $app->request->post("password");
  $username = $app->request->post("username");
  $name = $app->request->post("name");
  if(!isset($name)){
    $name = "";
  }
  if(!isset($email)||!isset($password)||!isset($username)){
    $app->render(400,ErrorCode::get(4));
    return;
  }
  if(User::isEmailExist($email)){
    $app->render(400,ErrorCode::get(5));
    return;
  }
  if(User::isUserNameExist($username)){
    $app->render(400,ErrorCode::get(6));
    return;
  }
  if(User::isReserved($username)){
    $app->render(400,ErrorCode::get(7));
    return;
  }
  if(User::isUsernameMalform($username)){
    $app->render(400,ErrorCode::get(8));
    return;
  }
  $id = User::add($username,$email,$password,$name);
  $app->render(200,array(
    'id'=>intval($id)
  ));
});
?>
