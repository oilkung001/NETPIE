<?php
/*
เปลี่ยนค่าโหนดเดิม
POST: /repos/:user/:repo/node/:id
PARAMETER:
  - access_token
  - value
RESPONSE:
  - id (node's id)
*/
$app->post('/repos/:repo/nodes/:id',function($repo,$nodeId) use ($app){
  $access_token = $app->request->post('access_token');
  $value = $app->request->post("value");
  if(!isset($value)){
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
  if(is_null($repo)){
    $app->render(400,ErrorCode::get(10));
    return ;
  }
  if(!Repo::hasWritePermission($repoId,$userId)){
    $app->render(400,ErrorCode::get(17));
    return ;
  }
  if(!Node::isExist($nodeId,$repoId)){
    $app->render(400,ErrorCode::get(20));
    return ;
  }
  Node::update($nodeId,$value);
  $app->render(200,array(
    'id'=>intval($nodeId)
  ));
});
?>
