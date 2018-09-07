<?php
  /*
  ร้บหัวข้อเรื่อง
  GET: /repos/:repo/stories/:id
  PARAMETER:
    - access_token (optional for private repo)
  RESPONSE:
    - id (story's id)
    - name
  */
$app->get('/repos/:repo/stories/:id',function($repo,$storyId) use ($app,$config){
  $access_token = $app->request->get('access_token');
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
  if(!Story::isExist($storyId,$repoId)){
    $app->render(400,ErrorCode::get(20));
    return ;
  }
  $result = Story::get($storyId);
  unset($result['repoid']);
  $result['graph'] = Story::getKnowledge($storyId);
  $app->render(200,$result);
});
?>
