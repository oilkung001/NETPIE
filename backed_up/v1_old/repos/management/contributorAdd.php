<?php
/*
เพิ่มผู้ร่วมพัฒนา
POST: /repos/:user/:repo/contributor
PARAMETER:
  - username
  - access_token (owner only)
RESPONSE:
  - id (repo's id)
*/
$app->post('/:user/:repo/contributor',function($username,$reponame) use ($app,$config,$pdo){
  $access_token = $app->request->post('access_token');
  $addUser = $app->request->post('username');
  if(!$access_token||!$addUser){
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
        'code' => 18,
        'message' => 'only owner can add contributor',
      ),
    ));
    return;
  }
  $addUserId = User::getId($addUser);
  if(!$addUserId){
    $app->render(404,array(
      'error'=> array(
        'code' => 22,
        'message' => 'username '.$addUser.' isn\'t exist',
      ),
    ));
    return;
  }
  if(Repo::isExistContributor($repoId,$addUserId)){
    $app->render(400,array(
      'error'=> array(
        'code' => 19,
        'message' => $addUser.' has been already add to '.$username.'/'.$reponame,
      ),
    ));
    return;
  }
  Repo::addContributor($repoId,$addUserId);
  $app->render(200,array(
    'id'=>$repoId
  ));
});
?>
