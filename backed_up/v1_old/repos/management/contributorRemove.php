<?php
/*
ลบผู้ร่วมพัฒนา
DELETE: /repos/:user/:repo/contributor
PARAMETER:
  - username
  - access_token (owner only)
RESPONSE:
  - id (repo's id)
*/
$app->delete('/:user/:repo/contributor',function($username,$reponame) use ($app,$config,$pdo){
  $access_token = $app->request->delete('access_token');
  $deleteUser = $app->request->delete('username');
  if(!$access_token||!$deleteUser){
    $app->render(400,array(
        'error' => array(
          'code' => 17,
          'message' => 'require parameter access_token and username'
        )
    ));
    return;
  }
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
        'code' => 20,
        'message' => 'only owner can remove contributor',
      ),
    ));
    return;
  }
  $deleteUserId = User::getId($deleteUser);
  if(!$deleteUserId){
    $app->render(404,array(
      'error'=> array(
        'code' => 22,
        'message' => 'username '.$deleteUser.' isn\'t exist',
      ),
    ));
    return;
  }
  if(!Repo::isExistContributor($repoId,$deleteUserId)){
    $app->render(400,array(
      'error'=> array(
        'code' => 21,
        'message' => $deleteUser.' isn\'t contribute in '.$username.'/'.$reponame,
      ),
    ));
    return;
  }
  Repo::removeContributor($repoId,$deleteUserId);
  $app->render(200,array(
    'id'=>$repoId
  ));
});
?>
