<?php
/*
เปลี่ยนค่าตัวแปร
POST: /repos/:repo/variables/:id
PARAMETER:
  - access_token
  - name
RESPONSE:
  - id (node's id)
*/
$app->post('/repos/:repo/variables/:id',function($repo,$varId) use ($app){
  $access_token = $app->request->post('access_token');
  $value = $app->request->post("value");
  $name = $app->request->post("name");
  if(!isset($value) && !isset($name)){
    $app->render(400,ErrorCode::get(32));
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
  if(!Variable::isExist($varId,$repoId)){
    $app->render(400,ErrorCode::get(33));
    return ;
  }
  Variable::update($varId,$name,$value);
  $app->render(200,array(
    'id'=>intval($varId)
  ));
});
?>
