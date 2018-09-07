<?php
/*
ลบตัวแปรก
DELETE: /repos/:repo/variable/id
PARAMETER:
  - access_token
RESPONSE:
  - id (story's id)
*/
$app->delete('/repos/:repo/variables/:id',function($repo,$varId) use ($app){
  $access_token = $app->request->post('access_token');
  if(!isset($access_token)){
    $app->render(400,ErrorCode::get(1));
    return;
  }
  $userId = Authen::getId($access_token);
  if(is_null($userId)){
    $app->render(400,ErrorCode::get(2));
    return;
  }
  $repoId = Repo::get($repo)['id'];
  if(is_null($repoId)){
    $app->render(400,ErrorCode::get(10));
    return ;
  }
  $repoId = Repo::get($repo)['id'];
  if(!Repo::hasWritePermission($repoId,$userId)){
    $app->render(400,ErrorCode::get(17));
    return ;
  }
  if(!Variable::isExist($varId,$repoId)){
    $app->render(400,ErrorCode::get(33));
    return ;
  }
  Variable::delete($varId);
  $app->render(200,array(
    'id'=>intval($varId)
  ));
});
?>
