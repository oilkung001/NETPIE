<?php
/*
ดึงค่าของโหนด
GET: /repos/:user/:repo/node/:id
PARAMETER:
  - access_token (optional for private repo)
RESPONSE
  - id
  - type
  - value
  - story {id,name}
//TODO: get repoId from StoryId and check is repoId same with repoId from prevent cross repo request
*/
$app->get('/:user/:repo/nodes/:id',function($username,$reponame,$nodeId) use ($app,$config,$pdo){
  $access_token = $app->request->get('access_token');
  $userid = null;
  if($access_token){
    $userid = jwtToUserId($access_token);
    if(!$userid){
      $app->render(401,array(
         'error' => array(
           'code' => 401,
           'message' => 'access_token is invalid'
         )
      ));
      return ;
    }
  }
  if(Repo::getId($username,$reponame,$userid)){
    $node = Node::get($nodeId);
    if(!$node){
      $app->render(400,array(
        'error' => array(
          'code' => 26,
          'message' => 'node_id '.$nodeId.' isn\'t exist'
        )
      ));
      return ;
    }
    $app->render(200,array($node));
  }else{
    $app->render(400,array(
      'error' => array(
        'code' => 13,
        'message' => 'respository '.$username.'/'.$reponame.' isn\'t found'
      )
    ));
  }
});
?>
