<?php
/*
//TODO: disable reserved name and begin with number
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
$app->post('/',function() use ($app,$config,$pdo){
  $email = $app->request->post("email");
  $password = $app->request->post("password");
  $username = $app->request->post("username");
  $name = $app->request->post("name");
  if(!isset($name)){
    $name = "";
  }
  if(!isset($email)||!isset($password)||!isset($username)){
    $app->render(400,array(
      'error'=>array(
        'code'=>4,
        'message'=>'parameter name,email,username and password is require for register',
      ),
    ));
    return;
  }
  if(User::isUserNameExist($username)){
    $app->render(400,array(
      'error'=>array(
        'code'=>6,
        'message'=>'this username has been already register',
        ),
    ));
    return;
  }
  if(User::isEmailExist($email)){
    $app->render(400,array(
      'error'=>array(
        'code'=>5,
        'message'=>'this email has been already register',
      ),
    ));
    return;
  }
  if(User::isReserved($username)){
    $app->render(400,array(
      'error'=>array(
        'code'=>7,
        'message'=>'username '.$username.' is reserved for system',
      ),
    ));
    return;
  }
  $id = User::add($username,$email,$password,$name);
  $app->render(200,array(
    'id'=>intval($id)
  ));
});
?>
