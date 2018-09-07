<?php
/*
change email
POST: /v1/users/email
PARAMETER:
  - email
  - access_token
RESPONSE:
  - (user) id
*/
$app->post('/email',function() use ($app,$config,$pdo){
  $access_token = $app->request->post('access_token');
  $email = $app->request->post('email');
  $id = jwtToUserId($access_token);
  if($id){
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $app->render(400,array(
         'error' => array(
           'code' => 9,
           'message' => 'email is invalid'
         )
      ));
    }else{
      $query = $pdo->select()->from('user')->where('user_id','=',$id);
      $result = $query->execute()->fetch();
      $pdo
        ->update(array(
          'user_email' => $email
        ))
        ->table('user')
        ->where('user_id', '=', $result['user_id'])
        ->execute();
        $app->render(200,array(
          'id'=>$result['user_id'],
        ));
    }
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
