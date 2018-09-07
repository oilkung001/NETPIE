<?php
/*
สร้างตัวแปรใหม่
POST: /repos/:repo/variable
PARAMETER:
  - name
  - value
  - access_token
RESPONSE:
  - id
*/
$app->post('/repos/:repo/variables',function($repo) use ($app,$config){
  $access_token = $app->request->post('access_token');
  $name = $app->request->post("name");
  $value = $app->request->post("value");
  if(!isset($name)){
    $app->render(400,ErrorCode::get(31));
  }
  if(!isset($value)){
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
  $result = Variable::add($repoId,$name,$value);
  $app->render(200,array(
    'id' => intval($result),
  ));
});

?>
