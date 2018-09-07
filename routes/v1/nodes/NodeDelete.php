<?php
/*
ลบโหนด
DELETE: /repos/:user/:repo/nodes/:id
PARAMETER:
  - access_token
RESPONSE:
  - id (node's id)
*/
$app->delete('/repos/:repo/nodes/:id',function($repo,$nodeId) use ($app){
  $access_token = $app->request->delete('access_token');
  $remove = $app->request->delete('remove');
  if(!isset($access_token)){
    $app->render(400,ErrorCode::get(1));
    return ;
  }
  $userId = Authen::getId($access_token);
  if(!isset($userId)){
    $app->render(400,ErrorCode::get(2));
    return ;
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
    $app->render(400,ErrorCode::get(23));
    return ;
  }
  if(isset($remove)){
    Node::remove($nodeId);
  }else{
    Node::delete($nodeId);
  }
  $app->render(200,array(
    'id' => intval($nodeId),
  ));
});
?>
