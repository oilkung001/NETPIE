<?php
/*
ยกเลิก repo ปัจจุบันจาก private (เปลี่ยนเป็น public)
DELETE: /repos/:user/:repo/private
PARAMETER:
  - access_token
RESPONSE:
  - id (repo's id)
*/
$app->delete('/:user/:repo/private',function($username,$reponame) use ($app,$config,$pdo){
  $access_token = $app->request->delete('access_token');
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
  $repoId = Repo::getId($username,$reponame,$userId);
  if(!$repoId){
    $app->render(404,array(
      'error'=> array(
        'code' => 13,
        'message' => 'respository '.$username.'/'.$reponame.' isn\'t found',
      ),
    ));
    return;
  }
  if(Repo::getOwner($repoId)!=$userId){
    $app->render(401,array(
      'error'=> array(
        'code' => 15,
        'message' => 'only owner can set private state',
      ),
    ));
    return;
  }
  Repo::setPrivate($repoId,false);
  $app->render(200,array(
    'id'=>$repoId
  ));
});
?>
