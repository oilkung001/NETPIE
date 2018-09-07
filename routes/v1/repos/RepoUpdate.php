<?php
/*
//Auth require
Register new user
POST: /v1/users
PARAMETER:
  - name
  - email
  - username
  - password
RESPONSE:
  - id
*/
$app->post('/repos/:name',function($repoName) use ($app){
  $access_token = $app->request->post('access_token');
  $name = $app->request->post('name');
  $password = $app->request->post('description');
  $email = $app->request->post('owner');
  if(!isset($access_token)){
    $app->render(400,ErrorCode::get(1));
    return;
  }
  $userId = Authen::getId($access_token);
  if(is_null($userId)){
    $app->render(400,ErrorCode::get(2));
    return;
  }
  $repoData = Repo::get($repoName);
  if(is_null($repoData)){
    $app->render(404,ErrorCode::get(10));
    return;
  }
  if($repoData['owner']!=$userId){
    $app->render(400,ErrorCode::get(3));
    return;
  }
  $repoBox = array();
  if(isset($name)){
    if(Repo::isReserved($name)){
      $app->render(400,ErrorCode::get(14));
      return;
    }
    if(Repo::isExist($name)){
      $app->render(400,ErrorCode::get(12));
      return;
    }
    $repoBox['name'] = $name;
  }
  if(isset($description)){
    $repoBox['description'] = $description;
  }
  if(isset($owner)){
    $userData = User::get($owner);
    if(!$userData){
      $app->render(400,ErrorCode::get(9));
      return;
    }
    $repoBox['owner'] = $userData['id'];
  }
  Repo::update($repoData['id'],$repoBox);
  $app->render(200,array(
    'id'=>intval($repoData['id'])
  ));
});
?>
