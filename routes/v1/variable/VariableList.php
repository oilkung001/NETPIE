<?php
/*
ร้บตัวแปรทั้งหมดของ repo นี้
GET: /repos/:repo/variables
PARAMETER:
  - access_token (optional for private repo)
RESPONSE:
  - varibles [{name,value}]
*/
$app->get('/repos/:repo/variables',function($repo) use ($app){
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
  if(is_null($repoId)){
    $app->render(400,ErrorCode::get(10));
    return ;
  }
  if(!Repo::hasReadPermission($repoId,$userId)){
    $app->render(400,ErrorCode::get(17));
    return ;
  }
  $result = Variable::getList($repoId);
  $app->render(200,array(
    'variables'=>$result
  ));
});
?>
