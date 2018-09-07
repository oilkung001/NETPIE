<?php
/*
Register new repo
POST: /v1/repos
PARAMETER:
  - name
  - description (optional)
  - access_token
RESPONSE:
  - id
*/
$app->post('/repos',function() use ($app){
  $access_token = $app->request->post("access_token");
  $name = $app->request->post("name");
  $description = $app->request->post("description");
  if(!isset($description)){
    $description = '';
  }
  if(!isset($access_token)){
    $app->render(400,ErrorCode::get(1));
    return;
  }
  $userId = Authen::getId($access_token);
  if(is_null($userId)){
    $app->render(400,ErrorCode::get(2));
  }
  if(!isset($description)){
    $description = '';
  }
  if(!isset($name)){
    $app->render(400,ErrorCode::get(11));
    return;
  }
  if(Repo::isReserved($name)){
    $app->render(400,ErrorCode::get(14));
    return;
  }
  if(Repo::isExist($name)){
    $app->render(400,ErrorCode::get(12));
    return;
  }
  $id = Repo::add($userId,$name,$description);
  $app->render(200,array(
    'id'=>intval($id)
  ));
});
?>
