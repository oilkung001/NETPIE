<?php
/*
get repo info
POST: /v1/repos
PARAMETER:
  - (NONE)
RESPONSE:
  - id
*/
$app->get('/repos/:name',function($repoName) use ($app){
  $data = Repo::get($repoName);
  if(!$data){
    $app->render(404,ErrorCode::get(10));
    return;
  }
  $data['id'] = intval($data['id']);
  unset($data['owner']);
  $app->render(200, $data);
});
?>
