<?php
/*
get user information
GET: /v1/users/:user
PARAMETER:
RESPONSE:
  - id
  - username
  - name
  - email
  - bio
*/
$app->get('/:user',function($username) use ($app,$config,$pdo){
  $query = $pdo->select()->from("user")->where("user_name","=",$username);
  $result = $query->execute();
  if($result->rowCount()!=0){
    $result = $result->fetch();
    $app->render(200,array(
      'id'=>$result["user_id"],
      'username'=>$result["user_name"],
      'name'=>$result["user_fullname"],
      'email'=> $result["user_email"],
      'bio'=> $result["user_bio"],
    ));
  }else{
    $app->render(404,array(
      'error' => array(
        'code' => 3,
        'message' => 'username '.$username.' not exist',
      )
    ));
  }
});

?>
