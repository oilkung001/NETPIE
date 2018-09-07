<?php
/*
ลบเส้นเชื่อม
DELETE: /repos/:user/:repo/edges
PARAMETER:
  - current
  - next
  - access_token (optional for private repo)
RESPONSE:
  - id (Edge id)
*/
$app->delete('/repos/:repo/edges',function($repo) use ($app){
  $access_token = $app->request->delete('access_token');
  $cNode = $app->request->delete('current');
  $nNode = $app->request->delete('next');
  if(!isset($access_token)){
    $app->render(400,ErrorCode::get(1));
    return;
  }
  $userId = Authen::getId($access_token);
  if(is_null($userId)){
    $app->render(400,ErrorCode::get(2));
    return;
  }
  if(!isset($cNode)||!isset($nNode)){
    $app->render(400,ErrorCode::get(25));
  }
  $repoId = Repo::get($repo);
  if(is_null($repo)){
    $app->render(400,ErrorCode::get(10));
    return ;
  }
  if(!Repo::hasWritePermission($repoId,$userId)){
    $app->render(400,ErrorCode::get(17));
    return ;
  }
  if($cNode != 0 && !Node::isExist($cNode,$repoId)){
    $app->render(400,ErrorCode::get(26));
    return ;
  }
  if(!Node::isExist($nNode,$repoId)){
    $app->render(400,ErrorCode::get(27));
    return ;
  }
  $result = Edge::get($cNode,$nNode);
  if(is_null($result)){
    $app->render(400,ErrorCode::get(28));
    return ;
  }
  Edge::remove(intval($cNode),intval($nNode));
  $app->render(200,array(
    'id' => $result['id'],
  ));
});
