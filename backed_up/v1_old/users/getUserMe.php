<?php
/*
get user information
GET: /v1/users/
PARAMETER:
  - access_token
RESPONSE:
  - id
  - username
  - name
  - email
  - bio
*/
$app->get('/',function() use ($app,$config,$pdo){
  $access_token = $app->request->get('access_token');
  $id = jwtToUserId($access_token);
  if($id){
    $query = $pdo->select()->from('user')->where('user_id','=',$id);
    $result = $query->execute()->fetch();
    $app->render(200,array(
      'id'=>$result['user_id'],
      'username'=>$result['user_name'],
      'name'=>$result['user_fullname'],
      'email'=> $result['user_email'],
      'bio'=> $result['user_bio'],
    ));
  }else{
    $app->render(401,array(
       'error' => array(
         'code' => 401,
         'message' => 'access_token is invalid'
       )
    ));
  }
});

?>
