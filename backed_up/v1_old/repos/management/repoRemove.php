<?php
/*
Remove Exist repo
DELETE: /v1/repos/:user/:repo
PARAMETER:
  - access_token
RESPONSE:
  - id (repo's id)
*/
$app->delete('/:user/:repo',function($username,$reponame) use ($app,$config,$pdo){
  $access_token = $app->request->delete('access_token');
  $userId = jwtToUserId($access_token);
  if(!$userId){
    $app->render(401,array(
       'error' => array(
         'code' => 401,
         'message' => 'access_token is invalid'
       )
    ));
  }else{
    $repoId = Repo::getid($username,$reponame);
    if($repoId && Repo::getOwner($repoId) == $userId){
      Repo::remove($repoId);
      $app->render(200,array(
        'id'=>intval($repoId)
      ));
    }else{
      $app->render(401,array(
         'error' => array(
           'code' => 14,
           'message' => 'only owner can delete repository'
         )
      ));
    }
  }
});
?>
