<?php
/*
Retivive Repo data from DB
GET: /v1/repos/:user/:repo
PARAMETER:
  - access_token (Optional for private repo only)
RESPONSE:
  - id (repo's id)
  - name
  - description
  - private (boolean)
*/
$app->get('/:user/:repo',function($username,$reponame) use ($app,$config,$pdo){
  $access_token = $app->request->get('access_token');
  $userId = null;
  if($access_token){
    $userId = jwtToUserId($access_token);
    if(!$userId){
      $app->render(401,array(
         'error' => array(
           'code' => 401,
           'message' => 'access_token is invalid'
         )
      ));
      return;
    }
  }
  $repoId = Repo::getId($username,$reponame,$userId);
  if(!$repoId){
    $app->render(404,array(
      'error'=> array(
        'code' => 13,
        'message' => 'respository '.$username.'/'.$reponame.' isn\'t found',
      ),
    ));
  }else{
    $repo = Repo::get($repoId);
    $app->render(200,array(
      'id'=>intval($repo['repo_id']),
      'name'=>$repo['repo_name'],
      'description'=>$repo['repo_description'],
      'private'=>($repo['repo_private']==1)?true:false,
    ));
  }
});
?>
