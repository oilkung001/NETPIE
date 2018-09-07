<?php
/*
//Auth require
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
$app->post('/users/:username',function($username) use ($app){
  $access_token = $app->request->post('access_token');
  $name = $app->request->post('name');
  $password = $app->request->post('password');
  $email = $app->request->post('email');
  $bio = $app->request->post('bio');
  if(!isset($access_token)){
    $app->render(400,ErrorCode::get(1));
    return;
  }
  $userId = Authen::getId($access_token);
  if(!isset($userId)){
    $app->render(400,ErrorCode::get(2));
    return;
  }
  $userData = User::get($username);
  if($userData['id']!=$userId){
    $app->render(400,ErrorCode::get(3));
    return;
  }
  $userBox = array();
  if(isset($name)){
    $userBox['name'] = $name;
  }
  if(isset($bio)){
    $userBox['bio'] = $bio;
  }
  if(isset($email)){
    $userBox['email'] = $email;
  }
  if(isset($password)){
    $userBox['password'] = $password;
  }
  User::update($userId,$userBox);
  $app->render(200,array(
    'id'=>intval($userId)
  ));
});
?>
