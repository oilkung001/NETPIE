<?php
/*
ร้บหัวข้อเรื่อง
GET: /repos/:user/:repo/stories
PARAMETER:
  - access_token (optional for private repo)
RESPONSE:
  - id (repo's id)
  - stories (id (story's id),name)
*/
$app->get('/repos/:repo/stories',function($repo) use ($app){
  $access_token = $app->request->get('access_token');
  if(!isset($name)){
    $name = "";
  }
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
  if(!Repo::hasReadPermission($repoId,$userId)){
    $app->render(400,ErrorCode::get(17));
    return ;
  }
  $result = Story::getList($repoId);
  $app->render(200,array(
    'stories'=>$result
  ));
});
?>
