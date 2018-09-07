<?php
/*
ดูว่า repo ปัจจุบันเป็น Private อยู่หรือไม่
GET: /repos/:user/:repo/private
PARAMETER:
  - access_token (Optional for private repo only)
RESPONSE:
  - id (repo's id)
  - private (boolean)
*/
$app->get('/:user/:repo/private',function($username,$reponame) use ($app,$config,$pdo){
  $access_token = $app->request->get('access_token');
  $userId = null;
  if($access_token){
    $userId = jwtToUserId($access_token);
    if(!$userId){
      $app->render(401,array(
         'error' => array(
           'code' => 401,
           'message' => 'access_token is invalid'
         )
      ));
      return;
    }
  }
  $repoId = Repo::getId($username,$reponame,$userId);
  $private = Repo::getPrivate($repoId);
  if(!$repoId || $private == null){
    $app->render(404,array(
      'error'=> array(
        'code' => 13,
        'message' => 'respository '.$username.'/'.$reponame.' isn\'t found',
      ),
    ));
  }else{
    $app->render(200,array(
      'id'=>$repoId,
      'private'=>$private,
    ));
  }
});
?>
