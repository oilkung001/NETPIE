<?php
/*
เปลี่ยนชื่อหัวข้อเรื่อง
POST: /repos/:repo/stories/:id
PARAMETER:
  - access_token
  - name
RESPONSE:
  - id (node's id)
*/
$app->post('/repos/:repo/stories/:id',function($repo,$storyId) use ($app){
  $access_token = $app->request->post('access_token');
  $name = $app->request->post("name");
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
  if(!Repo::hasWritePermission($repoId,$userId)){
    $app->render(400,ErrorCode::get(17));
    return ;
  }
  if(!Story::isExist($storyId,$repoId)){
    $app->render(400,ErrorCode::get(20));
    return ;
  }
  Story::update($storyId,$name);
  $app->render(200,array(
    'id'=>intval($storyId)
  ));
});
?>
