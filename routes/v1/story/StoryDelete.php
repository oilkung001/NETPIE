<?php
/*
ลบหัวข้อเรื่อง
DELETE: /repos/:repo/stories/id
PARAMETER:
  - access_token
RESPONSE:
  - id (story's id)
*/
$app->delete('/repos/:repo/stories/:id',function($repo,$storyId) use ($app){
  $access_token = $app->request->post('access_token');
  $remove = $app->request->delete('remove');
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
  if(!Repo::hasReadPermission($repoId,$userId)){
    $app->render(400,ErrorCode::get(17));
    return ;
  }
  if(!Story::isExist($storyId,$repoId)){
    $app->render(400,ErrorCode::get(20));
    return ;
  }
  if(isset($remove)){
    Story::remove($storyId);
  }else{
    Story::delete($storyId);
  }
  $app->render(200,array(
    'id'=>intval($storyId)
  ));
});
?>
