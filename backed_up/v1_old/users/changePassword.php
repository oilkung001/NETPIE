<?php
/*
change password
POST: /v1/users/password
PARAMETER:
  - password
  - newpassword
  - access_token
RESPONSE:
  - id.1
*/
$app->post('/password',function() use ($app,$config,$pdo){
  $access_token = $app->request->post('access_token');
  $password = $app->request->post('password');
  $newpassword = $app->request->post('newpassword');
  $id = jwtToUserId($access_token);
  if($id){
      $passwordHasher = new Pentagonal\Phpass\PasswordHash(8,false);
      $query = $pdo->select()->from('user')->where('user_id','=',$id);
      $result = $query->execute()->fetch();
      $isMatch = $passwordHasher->checkPassword($password,$result["user_hash"]);
      if(!$isMatch){
        $app->render(400,array(
           'error' => array(
             'code' => 8,
             'message' => 'password is mismatch'
           )
        ));
      }else{
        $pdo
          ->update(array(
            'user_hash' => $passwordHasher->hashPassword($newpassword)
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
