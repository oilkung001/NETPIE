<?php
$app->get('/users/:user/repos',function($username) use ($app){
  $access_token = $app->request->get('access_token');
  $userId = User::get($username);
  if(is_null($userId)){
    $app->render(400,ErrorCode::get(9));
    return;
  }
  $userId = $userId['id'];
  $viewerId = Authen::getId($access_token);
  $repoList = Repo::getByUserId($userId,$viewerId);
  $app->render(200,array(
    "id" => $userId,
    "repos"=>$repoList,
  ));
});
?>
