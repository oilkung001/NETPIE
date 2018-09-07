<?php
/*
สร้างหัวข้อเรื่องใหม่
POST: /repos/:repo/stories
PARAMETER:
  - name (optional)
  - order (optional)
  - access_token
RESPONSE:
  - id (story's id)
*/
$app->post('/repos/:repo/stories',function($repo) use ($app,$config){
  $access_token = $app->request->post('access_token');
  $name = $app->request->post("name");
  $order = $app->request->post("order");
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
  if(isset($order) && Story::isExistOrder($repoData['id'])){
    $app->render(400,ErrorCode::get(19));
    return ;
  }
  if(isset($order)){
    $order = intval($order);
  }else{
    $order = null;
  }
  $result = Story::add($repoId,$name,$order);
  $app->render(200,array(
    'id' => intval($result),
  ));
});

?>
