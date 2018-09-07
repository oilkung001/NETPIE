<?php
/*
สร้างโหนดใหม่
POST: /repos/:user/:repo/nodes
PARAMETER:
  - access_token
  - story_id
  - value
RESPONSE:
  - id (node's id)
*/
$app->post('/repos/:repo/nodes',function($repo) use ($app){
  $access_token = $app->request->post('access_token');
  $value = $app->request->post("value");
  $storyId = $app->request->post("story_id");
  $type = $app->request->post("type");
  if(!isset($access_token)){
    $app->render(400,ErrorCode::get(1));
    return ;
  }
  $userId = Authen::getId($access_token);
  if(is_null($userId)){
    $app->render(400,ErrorCode::get(2));
    return ;
  }
  if(!isset($storyId)||!isset($type)){
    $app->render(400,ErrorCode::get(21));
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
  if(!Story::isExist($storyId,$repoId)){
    $app->render(400,ErrorCode::get(20));
    return ;
  }
  if(!Node::isAcceptType($type)){
    $app->render(400,ErrorCode::get(22));
    return ;
  }
  if(!isset($value)){
    $value = '';
  }
  $result = Node::add($storyId,$type,$value,$repoId);
  $app->render(200,array(
    'id' => $result,
  ));
});
?>
