<?php
/*
ดึงค่าของโหนด
GET: /repos/:user/:repo/node/:id
PARAMETER:
  - access_token (optional for private repo)
RESPONSE
  - id
  - type
  - value
  - story_id
*/
$app->get('/repos/:repo/nodes/:id',function($repo,$nodeId) use ($app){
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
  if(is_null($repo)){
    $app->render(400,ErrorCode::get(10));
    return ;
  }
  if(!Repo::hasReadPermission($repoId,$userId)){
    $app->render(400,ErrorCode::get(17));
    return ;
  }
  if(!Node::isExist($nodeId,$repoId)){
    $app->render(400,ErrorCode::get(20));
    return ;
  }
  $result = Node::get($nodeId);
  unset($result['repoid']);
  $result['story_id'] = $result['storyid'];
  unset($result['storyid']);
  $app->render(200,$result);
});

?>
