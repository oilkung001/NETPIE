<?php
/*
แก้ไข description ของ repo
POST: /repos/:user/:repo/description
PARAMETER:
  - access_token
  - description
RESPONSE:
  - id (repo's id)
*/
$app->post('/:user/:repo/description',function($username,$reponame) use ($app,$config,$pdo){
  $access_token = $app->request->post('access_token');
  $description = $app->request->post('description');
  if(!$access_token||!$description){
    $app->render(400,array(
        'error' => array(
          'code' => 401,
          'message' => 'access_token is invalid'
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
        'code' => 16,
        'message' => 'only owner can set description',
      ),
    ));
    return;
  }
  Repo::setDescription($repoId,$description);
  $app->render(200,array(
    'id'=>$repoId
  ));
});
?>
