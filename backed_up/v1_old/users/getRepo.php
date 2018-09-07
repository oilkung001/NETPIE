<?php
/*
get user information
GET: /v1/users/:user/repos
PARAMETER:
  - access_token (require for view private repo)
RESPONSE:
  - id (user_id)
  - repos (array)
*/
$app->get('/:users/repos',function($username) use ($app,$config,$pdo){
  $access_token = $app->request->get('access_token');
  $userId = User::getId($username);
  if(isset($access_token)){
    $viewerId = jwtToUserId($access_token);
    if(!$viewerId){
      $app->render(401,array(
         'error' => array(
           'code' => 401,
           'message' => 'access_token is invalid'
         )
      ));
      return;
    }
  }else{
    $viewerId = null;
  }
  $repoList = Repo::getList($userId,$viewerId);
  $app->render(200,array(
    "id" => $userId,
    "repos"=>$repoList,
  ));

});

?>
