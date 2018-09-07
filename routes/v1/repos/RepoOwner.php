<?php
/*
get repo owner info
GET: /v1/repos/owner
PARAMETER:
  - (NONE)
RESPONSE:
  - id
  - name
  - username
  - email
  - bio
*/
$app->get('/repos/:name/owner',function($repoName) use ($app){
  $repoData = Repo::get($repoName);
  if(!$repoData){
    $app->render(404,ErrorCode::get(10));
  }
  $ownerData = User::get($repoData['owner']);
  $app->render(200, $ownerData);
});
?>
